<?php

namespace App\Twig;

use App\Translation\LocaleAvailability;
use App\Translation\LocaleCatalog;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Expose les données de locale aux templates Twig.
 *
 *   {{ available_locales()|join(',') }}        → "fr,en"
 *   {{ supported_locales() }}                  → ['fr', 'bg', ..., 'sv']
 *   {{ locale_name('en') }}                    → "English"
 *   {{ is_locale_available('en') }}            → true|false
 */
final class LocaleExtension extends AbstractExtension
{
    public function __construct(private readonly LocaleAvailability $availability)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('available_locales',    [$this, 'availableLocales']),
            new TwigFunction('supported_locales',    [LocaleCatalog::class, 'supportedLocales']),
            new TwigFunction('locale_name',          [LocaleCatalog::class, 'getName']),
            new TwigFunction('is_locale_available',  [$this, 'isLocaleAvailable']),
        ];
    }

    public function availableLocales(): array
    {
        return $this->availability->availableLocales();
    }

    public function isLocaleAvailable(string $locale): bool
    {
        return \in_array($locale, $this->availability->availableLocales(), true);
    }
}
