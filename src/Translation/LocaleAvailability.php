<?php

namespace App\Translation;

use App\Repository\TranslationRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Calcule quelles locales sont "publiquement disponibles" en fonction
 * de la complétude des traductions en base.
 *
 * La locale de référence (fr) est toujours disponible.
 * Les autres le sont si elles dépassent LocaleCatalog::AVAILABILITY_THRESHOLD.
 */
final class LocaleAvailability
{
    public function __construct(
        private readonly TranslationRepository $repo,
        #[Autowire(service: 'cache.app')]
        private readonly CacheInterface $cache,
    ) {
    }

    /**
     * @return string[] Liste des locales disponibles (inclut toujours 'fr').
     */
    public function availableLocales(): array
    {
        return $this->cache->get('civitalisme.translations.availability', function (ItemInterface $item) {
            $item->expiresAfter(900); // 15 min

            try {
                $stats = $this->repo->getCompletionStats(LocaleCatalog::supportedLocales());
            } catch (\Throwable) {
                return ['fr'];
            }

            $available = ['fr'];
            foreach ($stats as $locale => $s) {
                if ($locale === 'fr') continue;
                if ($s['total'] === 0) continue; // aucune clé → rien n'est traduisible
                if ($s['percent'] >= LocaleCatalog::AVAILABILITY_THRESHOLD) {
                    $available[] = $locale;
                }
            }
            return array_values(array_unique($available));
        });
    }

    public function invalidate(): void
    {
        $this->cache->delete('civitalisme.translations.availability');
    }
}
