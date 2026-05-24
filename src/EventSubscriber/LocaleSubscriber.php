<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Résout la locale depuis (par ordre de priorité) :
 *   1. Le paramètre {_locale} dans l'URL
 *   2. Le cookie civitalisme_locale
 *   3. L'en-tête Accept-Language
 *   4. Fallback 'fr'
 *
 * Persiste le choix dans un cookie 1 an.
 */
final class LocaleSubscriber implements EventSubscriberInterface
{
    public const SUPPORTED = [
        'fr', 'en', 'de', 'es', 'it', 'pt', 'nl', 'pl', 'ro', 'cs',
        'sk', 'hu', 'sv', 'da', 'fi', 'el', 'bg', 'hr', 'sl', 'lt',
        'lv', 'et', 'mt', 'ga',
    ];

    private const DEFAULT_LOCALE  = 'fr';
    private const COOKIE_NAME     = 'civitalisme_locale';
    private const COOKIE_LIFETIME = 31536000; // 1 an

    private ?string $resolvedLocale = null;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST  => [['onKernelRequest', 20]],
            KernelEvents::RESPONSE => [['onKernelResponse', 0]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $locale  = $this->resolveLocale($request);

        $request->setLocale($locale);
        $request->getSession()->set('_locale', $locale);
        $this->resolvedLocale = $locale;
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest() || $this->resolvedLocale === null) {
            return;
        }

        $response = $event->getResponse();
        $current  = $event->getRequest()->cookies->get(self::COOKIE_NAME);

        if ($current !== $this->resolvedLocale) {
            $response->headers->setCookie(Cookie::create(
                name:     self::COOKIE_NAME,
                value:    $this->resolvedLocale,
                expire:   time() + self::COOKIE_LIFETIME,
                path:     '/',
                secure:   true,
                httpOnly: true,
                sameSite: Cookie::SAMESITE_LAX,
            ));
        }
    }

    private function resolveLocale(Request $request): string
    {
        // Priorité 1 — paramètre {_locale} dans l'URL (route attribute)
        $locale = $request->attributes->get('_locale');
        if ($locale && $this->isSupported($locale)) {
            return $locale;
        }

        // Priorité 2 — cookie persisté
        $locale = $request->cookies->get(self::COOKIE_NAME);
        if ($locale && $this->isSupported($locale)) {
            return $locale;
        }

        // Priorité 3 — Accept-Language header (RFC 4647)
        $locale = $this->parseAcceptLanguage($request);
        if ($locale) {
            return $locale;
        }

        return self::DEFAULT_LOCALE;
    }

    private function parseAcceptLanguage(Request $request): ?string
    {
        $header = $request->headers->get('Accept-Language', '');
        if (!$header) {
            return null;
        }

        $tags = [];
        foreach (explode(',', $header) as $part) {
            $part = trim($part);
            if (preg_match('/^([a-zA-Z]{2,8}(?:-[a-zA-Z0-9]{2,8})*)\s*(?:;\s*q\s*=\s*([\d.]+))?$/', $part, $m)) {
                $tags[] = [strtolower($m[1]), isset($m[2]) ? (float) $m[2] : 1.0];
            }
        }

        usort($tags, fn($a, $b) => $b[1] <=> $a[1]);

        foreach ($tags as [$tag]) {
            if ($this->isSupported($tag)) {
                return $tag;
            }
            $primary = explode('-', $tag)[0];
            if ($this->isSupported($primary)) {
                return $primary;
            }
        }

        return null;
    }

    public static function isSupported(string $locale): bool
    {
        return \in_array($locale, self::SUPPORTED, true);
    }
}
