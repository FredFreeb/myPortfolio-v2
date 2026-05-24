<?php

namespace App\Translation;

use App\Repository\TranslationRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use function array_merge;
use function array_unique;
use function array_values;
use function is_file;

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
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
    }

    /**
     * @return string[] Liste des locales disponibles (inclut toujours 'fr').
     *
     * Une locale est disponible si :
     *   - elle a un fichier messages.{locale}.yaml dans /translations, OU
     *   - elle atteint le seuil de complétude en base (LocaleCatalog::AVAILABILITY_THRESHOLD)
     */
    public function availableLocales(): array
    {
        return $this->cache->get('civitalisme.translations.availability', function (ItemInterface $item) {
            $item->expiresAfter(900); // 15 min

            // Locales couvertes par des fichiers YAML statiques
            $yamlBacked = $this->localesWithYamlFile();

            try {
                $stats = $this->repo->getCompletionStats(LocaleCatalog::supportedLocales());
            } catch (\Throwable) {
                return \array_values(\array_unique(\array_merge(['fr'], $yamlBacked)));
            }

            $available = \array_merge(['fr'], $yamlBacked);
            foreach ($stats as $locale => $s) {
                if ($locale === 'fr') continue;
                if ($s['total'] === 0) continue;
                if ($s['percent'] >= LocaleCatalog::AVAILABILITY_THRESHOLD) {
                    $available[] = $locale;
                }
            }
            return \array_values(\array_unique($available));
        });
    }

    /** @return string[] */
    private function localesWithYamlFile(): array
    {
        $dir = $this->projectDir . '/translations';
        $locales = [];
        foreach (LocaleCatalog::supportedLocales() as $locale) {
            if ($locale === 'fr') continue;
            if (\is_file($dir . '/messages.' . $locale . '.yaml')) {
                $locales[] = $locale;
            }
        }
        return $locales;
    }

    public function invalidate(): void
    {
        $this->cache->delete('civitalisme.translations.availability');
    }
}
