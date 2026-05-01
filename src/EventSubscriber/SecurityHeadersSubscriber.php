<?php

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Adds security-related HTTP headers to every main response.
 *
 * Headers added:
 *  - Content-Security-Policy  : restricts resource origins to prevent XSS / data injection
 *  - X-Frame-Options          : blocks clickjacking via iframes
 *  - X-Content-Type-Options   : prevents MIME-type sniffing
 *  - Referrer-Policy          : limits referrer leakage
 *  - Permissions-Policy       : disables unused browser features
 *  - Strict-Transport-Security: enforces HTTPS (production only)
 */
final class SecurityHeadersSubscriber implements EventSubscriberInterface
{
    public function __construct(
        #[Autowire('%kernel.environment%')]
        private readonly string $environment,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::RESPONSE => 'onKernelResponse'];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $headers = $event->getResponse()->headers;

        // Content-Security-Policy
        // 'unsafe-inline' on script-src is required for Symfony's importmap (inline <script type="importmap">
        // and <script type="module">). Replace with nonces for stricter enforcement.
        // 'unsafe-inline' on style-src covers potential framework-injected inline styles.
        // media-src and frame-src allow the Synthesia video embed used on the civitalisme page.
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: blob:",
            "media-src 'self' https://share.synthesia.io",
            "frame-src https://share.synthesia.io",
            "font-src 'self'",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);

        $headers->set('Content-Security-Policy', $csp);
        $headers->set('X-Frame-Options', 'DENY');
        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // HSTS is only meaningful over HTTPS — restrict to production to avoid dev issues.
        if ('prod' === $this->environment) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
    }
}
