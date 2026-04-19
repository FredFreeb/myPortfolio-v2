<?php

namespace App\Translation;

use App\Repository\TranslationRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;

/**
 * Décorateur du traducteur Symfony qui ajoute les traductions stockées en BDD.
 *
 * Ordre de résolution :
 *   1. Dictionnaire DB pour le locale demandé
 *   2. Dictionnaire DB pour le locale de fallback (fr)
 *   3. Traducteur Symfony natif (catalogues YAML classiques si présents)
 *   4. Clé brute (comportement Symfony par défaut)
 *
 * Les dictionnaires sont mis en cache dans le pool applicatif ; la clé de
 * cache intègre le locale et le domain. L'admin invalide en appelant
 * `invalidateCache()` après chaque save.
 */
#[AsDecorator(decorates: 'translator.default')]
final class DatabaseAwareTranslator implements TranslatorInterface, TranslatorBagInterface, LocaleAwareInterface
{
    private const CACHE_PREFIX = 'civitalisme.translations.';

    /** @var array<string, array<string, string>> Cache in-memory par "locale.domain". */
    private array $local = [];

    public function __construct(
        #[AutowireDecorated]
        private readonly TranslatorInterface&TranslatorBagInterface&LocaleAwareInterface $inner,
        private readonly TranslationRepository $repo,
        #[Autowire(service: 'cache.app')]
        private readonly CacheItemPoolInterface $cache,
        #[Autowire('%kernel.default_locale%')]
        private readonly string $fallbackLocale = 'fr',
    ) {
    }

    public function trans(?string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        if ($id === null || $id === '') {
            return '';
        }
        $domain ??= 'messages';
        $locale ??= $this->getLocale();

        $value = $this->lookup($id, $locale, $domain);
        if ($value === null && $locale !== $this->fallbackLocale) {
            $value = $this->lookup($id, $this->fallbackLocale, $domain);
        }
        if ($value !== null) {
            return $parameters === [] ? $value : strtr($value, $parameters);
        }

        // Fallback sur le traducteur natif (catalogues YAML ou clé brute)
        return $this->inner->trans($id, $parameters, $domain, $locale);
    }

    private function lookup(string $id, string $locale, string $domain): ?string
    {
        $dict = $this->loadDict($locale, $domain);
        return $dict[$id] ?? null;
    }

    /**
     * @return array<string, string>
     */
    private function loadDict(string $locale, string $domain): array
    {
        $key = $locale . '.' . $domain;
        if (isset($this->local[$key])) {
            return $this->local[$key];
        }

        $cacheItem = $this->cache->getItem(self::CACHE_PREFIX . $key);
        if ($cacheItem->isHit()) {
            return $this->local[$key] = $cacheItem->get();
        }

        try {
            $dict = $this->repo->findLocaleDictionary($locale, $domain);
        } catch (\Throwable) {
            // Table pas encore migrée ou DB indisponible : on renvoie un dico vide
            $dict = [];
        }
        $cacheItem->set($dict);
        $cacheItem->expiresAfter(3600);
        $this->cache->save($cacheItem);

        return $this->local[$key] = $dict;
    }

    /**
     * Invalide le cache pour un locale (ou tous si null). Appelé par l'admin.
     */
    public function invalidateCache(?string $locale = null): void
    {
        $this->local = [];
        if ($locale === null) {
            $this->cache->clear(self::CACHE_PREFIX);
            return;
        }
        foreach (['messages'] as $domain) {
            $this->cache->deleteItem(self::CACHE_PREFIX . $locale . '.' . $domain);
        }
    }

    // ─── Délégation au traducteur natif ──────────────────────────────────

    public function getCatalogue(?string $locale = null): MessageCatalogueInterface
    {
        return $this->inner->getCatalogue($locale);
    }

    public function getCatalogues(): array
    {
        return $this->inner->getCatalogues();
    }

    public function setLocale(string $locale): void
    {
        $this->inner->setLocale($locale);
    }

    public function getLocale(): string
    {
        return $this->inner->getLocale();
    }
}
