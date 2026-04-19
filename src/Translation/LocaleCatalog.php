<?php

namespace App\Translation;

/**
 * Catalogue central des 24 locales officielles de l'UE.
 * Source unique partagée entre le subscriber, l'admin, le lang switcher
 * et les templates Twig.
 */
final class LocaleCatalog
{
    /**
     * @var array<string, string> locale → nom endonyme (dans sa propre langue)
     */
    public const LOCALES = [
        'fr' => 'Français',
        'bg' => 'Български',
        'cs' => 'Čeština',
        'da' => 'Dansk',
        'de' => 'Deutsch',
        'el' => 'Ελληνικά',
        'en' => 'English',
        'et' => 'Eesti',
        'fi' => 'Suomi',
        'ga' => 'Gaeilge',
        'hr' => 'Hrvatski',
        'hu' => 'Magyar',
        'it' => 'Italiano',
        'lt' => 'Lietuvių',
        'lv' => 'Latviešu',
        'mt' => 'Malti',
        'nl' => 'Nederlands',
        'pl' => 'Polski',
        'pt' => 'Português',
        'ro' => 'Română',
        'sk' => 'Slovenčina',
        'sl' => 'Slovenščina',
        'es' => 'Español',
        'sv' => 'Svenska',
    ];

    /**
     * Seuil de complétude (%) au-dessus duquel une langue est considérée
     * "disponible" publiquement (le lang switcher la propose sans toast).
     */
    public const AVAILABILITY_THRESHOLD = 95;

    /**
     * @return string[]
     */
    public static function supportedLocales(): array
    {
        return array_keys(self::LOCALES);
    }

    public static function isSupported(string $locale): bool
    {
        return isset(self::LOCALES[$locale]);
    }

    public static function getName(string $locale): string
    {
        return self::LOCALES[$locale] ?? strtoupper($locale);
    }
}
