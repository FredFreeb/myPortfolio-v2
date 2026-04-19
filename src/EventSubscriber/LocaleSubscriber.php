<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Force la locale de la requête à partir du cookie `civitalisme_locale` écrit
 * par le lang switcher côté client. Si aucun cookie, fallback sur le
 * default_locale (fr).
 *
 * S'exécute AVANT LocaleListener natif (priorité > 16).
 */
final class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * Locales réellement supportées (même liste que LOCALES dans le JS).
     * @var string[]
     */
    public const SUPPORTED = [
        'fr', 'bg', 'cs', 'da', 'de', 'el', 'en', 'et', 'fi', 'ga',
        'hr', 'hu', 'it', 'lt', 'lv', 'mt', 'nl', 'pl', 'pt', 'ro',
        'sk', 'sl', 'es', 'sv',
    ];

    public function __construct(private readonly string $defaultLocale = 'fr')
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $request = $event->getRequest();
        $cookie  = $request->cookies->get('civitalisme_locale');
        $locale  = \in_array($cookie, self::SUPPORTED, true) ? $cookie : $this->defaultLocale;
        $request->setLocale($locale);
    }
}
