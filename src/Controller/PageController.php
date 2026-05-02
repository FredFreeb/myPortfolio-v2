<?php

namespace App\Controller;

use App\Content\CivitalismeContentProvider;
use App\Entity\ContactMessage;
use App\Entity\ProfileLink;
use App\Enum\LinkCategory;
use App\Enum\ProjectAudience;
use App\Form\CivitalismeContactType;
use App\Form\ContactType;
use App\Form\Model\ContactRequest;
use App\Repository\ExperienceRepository;
use App\Repository\ProfileLinkRepository;
use App\Repository\ProjectUpdateRepository;
use App\Repository\TestimonialRepository;
use App\Repository\TrainingRepository;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    /**
     * @var list<array{title: string, body: string}>
     */
    private const JOURNEY = [
        [
            'title' => 'Développement web full-stack',
            'body' => 'Je construis des interfaces claires, des back-offices durables et des parcours qui restent lisibles pour les équipes comme pour les visiteurs.',
        ],
        [
            'title' => 'Culture produit et autonomie',
            'body' => 'J’aime faire dialoguer design, technique et organisation pour livrer des sites qui vivent bien après leur mise en ligne.',
        ],
        [
            'title' => 'Transmission et structuration',
            'body' => 'Je privilégie les bases simples à maintenir : conventions claires, code relisible, documentation courte et administration adaptée au vrai besoin.',
        ],
    ];

    /**
     * @var list<string>
     */
    private const ENVIRONMENT = [
        'Symfony 8, Twig, Doctrine et un admin sobre pour éditer sans friction.',
        'Docker et des commandes de démarrage lisibles pour retrouver un environnement stable rapidement.',
        'Une approche privacy-friendly : peu de dépendances externes, données utiles uniquement, sécurité native Symfony.',
    ];

    /**
     * @var list<string>
     */
    private const PASSIONS = [
        'IA générative et outils capables d’augmenter le travail humain sans noyer la clarté.',
        'Poésie, rythme, typographie et narration visuelle comme prolongement du code.',
        'Explorations musicales et projets personnels où le web devient un espace sensible.',
    ];

    /**
     * @var list<array{label: string, value: string}>
     */
    private const PROFILE_HIGHLIGHTS = [
        [
            'label' => 'Stack',
            'value' => 'Symfony, Twig, Docker',
        ],
        [
            'label' => 'Approche',
            'value' => 'Clarté, narration, maintien simple',
        ],
        [
            'label' => 'Terrains',
            'value' => 'Culture, web éditorial, projets prospectifs',
        ],
    ];

    /**
     * Public profile links that should stay visible even when production data is incomplete.
     *
     * @var list<array{
     *     label: string,
     *     title: string,
     *     subtitle: ?string,
     *     url: string,
     *     description: string,
     *     badge: string,
     *     theme: string,
     *     kicker: string,
     *     category: string,
     *     year: ?string
     * }>
     */
    private const DEFAULT_NETWORK_LINKS = [
        [
            'label' => 'Code public',
            'title' => 'Code public',
            'subtitle' => null,
            'url' => 'https://github.com/FredFreeb',
            'description' => 'Mes bases, prototypes et explorations autour du web, de l\'UI et des projets personnels.',
            'badge' => 'GH',
            'theme' => 'github',
            'kicker' => 'Réseau',
            'category' => 'network',
            'year' => null,
        ],
        [
            'label' => 'Trajectoire pro',
            'title' => 'Trajectoire pro',
            'subtitle' => null,
            'url' => 'https://www.linkedin.com/in/FredFreeb',
            'description' => 'Une lecture plus professionnelle du parcours, des responsabilités et du positionnement actuel.',
            'badge' => 'IN',
            'theme' => 'linkedin',
            'kicker' => 'Réseau',
            'category' => 'network',
            'year' => null,
        ],
    ];

    public function __construct(
        #[Autowire('%env(string:APP_ALBUM_URL)%')]
        private readonly string $albumUrl,
        #[Autowire('%env(string:APP_CIVITALISME_VIDEO_URL)%')]
        private readonly string $civitalismeVideoUrl,
        #[Autowire(service: 'limiter.contact_form')]
        private readonly RateLimiterFactory $contactFormLimiter,
        #[Autowire('%env(string:ADMIN_EMAIL)%')]
        private readonly string $adminEmail,
        private readonly MailerInterface $mailer,
    ) {
    }

    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(
        WorkRepository $workRepository,
        ProjectUpdateRepository $projectUpdateRepository,
        ProfileLinkRepository $profileLinkRepository,
        TestimonialRepository $testimonialRepository,
    ): Response {
        $networkLinks = $this->withDefaultNetworkLinks(
            $this->formatProfileLinks(
                $profileLinkRepository->findPublishedByCategory(LinkCategory::Network, 2),
                true
            ),
            2
        );

        return $this->render('page/home.html.twig', [
            'featuredWorks' => $workRepository->findPublished(6),
            'publicHighlights' => $projectUpdateRepository->findPublishedByAudience(ProjectAudience::Public, 2),
            'institutionalHighlights' => $projectUpdateRepository->findPublishedByAudience(ProjectAudience::Institutional, 2),
            'contactLinks' => $networkLinks,
            'testimonials' => $testimonialRepository->findPublished(3),
        ]);
    }

    #[Route('/about', name: 'app_about', methods: ['GET'])]
    public function about(
        ExperienceRepository $experienceRepository,
        TrainingRepository $trainingRepository,
        TestimonialRepository $testimonialRepository,
        ProfileLinkRepository $profileLinkRepository,
    ): Response {
        $experiences = $experienceRepository->findPublished();
        $trainings = $trainingRepository->findPublished();
        $networkCards = $this->withDefaultNetworkLinks(
            $this->formatProfileLinks($profileLinkRepository->findPublishedByCategory(LinkCategory::Network))
        );
        $hobbyCards = $this->formatProfileLinks($profileLinkRepository->findPublishedByCategory(LinkCategory::Hobby));

        return $this->render('page/about.html.twig', [
            'journey' => self::JOURNEY,
            'environment' => self::ENVIRONMENT,
            'passions' => self::PASSIONS,
            'profileHighlights' => self::PROFILE_HIGHLIGHTS,
            'experienceTimeline' => $experiences,
            'trainingTimeline' => $trainings,
            'networkCards' => $networkCards,
            'hobbyCards' => $hobbyCards,
            'testimonials' => $testimonialRepository->findPublished(4),
            'albumUrl' => $this->albumUrl,
            'contactLinks' => $networkCards,
        ]);
    }

    #[Route('/civitalisme', name: 'app_civitalisme', methods: ['GET'])]
    public function civitalisme(
        CivitalismeContentProvider $contentProvider,
    ): Response {
        // Both Civitalisme pages are entirely data-driven via CivitalismeContentProvider.
        // No DB calls: keeps them fast, version-controllable and resilient when the
        // admin/database is offline.
        return $this->render('page/civitalisme/index.html.twig', [
            'content' => $contentProvider->publicPage(),
            'civitalismeVideoUrl' => $this->civitalismeVideoUrl,
        ]);
    }

    #[Route('/civitalisme/cadre-institutionnel', name: 'app_civitalisme_technical', methods: ['GET', 'POST'])]
    public function civitalismeTechnical(
        Request $request,
        CivitalismeContentProvider $contentProvider,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
    ): Response {
        $contactRequest = new ContactRequest();
        $contactForm    = $this->createForm(CivitalismeContactType::class, $contactRequest);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            // Honeypot anti-spam
            if (!empty($contactRequest->getWebsite())) {
                $this->addFlash('success', 'Merci, votre message a bien été enregistré.');
                return $this->redirectToRoute('app_civitalisme_technical', ['_fragment' => 'contact-institutionnel']);
            }

            $message = (new ContactMessage())
                ->setName((string) $contactRequest->getName())
                ->setEmail((string) $contactRequest->getEmail())
                ->setCompany($contactRequest->getCompany())
                ->setSubject((string) $contactRequest->getSubject())
                ->setMessage((string) $contactRequest->getMessage());

            $entityManager->persist($message);
            $entityManager->flush();

            try {
                $notification = (new TemplatedEmail())
                    ->to($this->adminEmail)
                    ->replyTo((string) $contactRequest->getEmail())
                    ->subject(sprintf('[Civitalisme] %s — %s', $contactRequest->getName(), $contactRequest->getSubject()))
                    ->htmlTemplate('email/contact_notification.html.twig')
                    ->context([
                        'name'         => $contactRequest->getName(),
                        'contactEmail' => $contactRequest->getEmail(),
                        'company'      => $contactRequest->getCompany(),
                        'subject'      => $contactRequest->getSubject(),
                        'message'      => $contactRequest->getMessage(),
                    ]);
                $mailer->send($notification);
            } catch (\Throwable) {
                // L'e-mail n'est pas critique — le message est déjà en base
            }

            $this->addFlash('success', 'Merci, votre message a bien été reçu. Nous vous répondrons dans les meilleurs délais.');
            return $this->redirectToRoute('app_civitalisme_technical', ['_fragment' => 'contact-institutionnel']);
        }

        return $this->render('page/civitalisme/technical.html.twig', [
            'content'     => $contentProvider->technicalPage(),
            'contactForm' => $contactForm,
        ]);
    }

    #[Route('/civitalisme/{audience}', name: 'app_civitalisme_audience', requirements: ['audience' => 'grand-public|institutionnel'], methods: ['GET'])]
    public function civitalismeAudience(string $audience): Response
    {
        $projectAudience = ProjectAudience::tryFrom($audience);

        if (!$projectAudience instanceof ProjectAudience) {
            throw $this->createNotFoundException();
        }

        return $projectAudience === ProjectAudience::Public
            ? $this->redirectToRoute('app_civitalisme')
            : $this->redirectToRoute('app_civitalisme_technical');
    }

    #[Route('/mentions-legales', name: 'app_legal_notice', methods: ['GET'])]
    public function legalNotice(ProfileLinkRepository $profileLinkRepository): Response
    {
        return $this->render('page/legal/mentions.html.twig', [
            'editor' => [
                'name' => 'Frederic Fribel',
                'role' => 'Editeur et responsable de publication',
                'site' => 'Portfolio + Civitalisme',
            ],
            'contactLinks' => $this->withDefaultNetworkLinks(
                $this->formatProfileLinks(
                    $profileLinkRepository->findPublishedByCategory(LinkCategory::Network, 2),
                    true
                ),
                2
            ),
            'hosting' => [
                'name' => 'Infomaniak Network SA',
                'body' => 'Le site est heberge sur une infrastructure VPS Infomaniak. Le serveur applicatif, la configuration Docker, la base de donnees et les sauvegardes applicatives sont administres par l editeur du site.',
                'address' => 'Rue Eugene Marziano 25, 1227 Les Acacias (GE), Suisse',
                'website' => 'https://www.infomaniak.com',
            ],
        ]);
    }

    #[Route('/politique-confidentialite', name: 'app_privacy_policy', methods: ['GET'])]
    public function privacyPolicy(): Response
    {
        return $this->render('page/legal/privacy.html.twig', [
            'retentionPolicy' => [
                'Les messages transmis via le formulaire sont conserves uniquement le temps necessaire pour traiter la demande et assurer un suivi raisonnable.',
                'Aucune base marketing n est constituee a partir des formulaires envoyes depuis ce site.',
                'Les messages peuvent etre supprimes depuis l administration une fois la demande traitee.',
            ],
            'storedData' => [
                'Nom',
                'Adresse e-mail',
                'Structure ou entreprise si elle est renseignee',
                'Objet du message',
                'Contenu du message',
            ],
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function contact(
        Request $request,
        EntityManagerInterface $entityManager,
        ProfileLinkRepository $profileLinkRepository,
    ): Response {
        $contactRequest = new ContactRequest();
        $form = $this->createForm(ContactType::class, $contactRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $limiterKey = sprintf(
                '%s|%s',
                $request->getClientIp() ?? 'anonymous',
                strtolower((string) $contactRequest->getEmail())
            );
            $rateLimit = $this->contactFormLimiter->create($limiterKey)->consume(1);

            if (!$rateLimit->isAccepted()) {
                $this->addFlash('error', 'Le formulaire a été envoyé trop souvent. Merci de patienter quelques minutes.');

                return $this->redirectToRoute('app_contact');
            }

            if (!empty($contactRequest->getWebsite())) {
                $this->addFlash('success', 'Merci, ton message a bien été pris en compte.');

                return $this->redirectToRoute('app_contact');
            }

            $message = (new ContactMessage())
                ->setName((string) $contactRequest->getName())
                ->setEmail((string) $contactRequest->getEmail())
                ->setCompany($contactRequest->getCompany())
                ->setSubject((string) $contactRequest->getSubject())
                ->setMessage((string) $contactRequest->getMessage());

            $entityManager->persist($message);
            $entityManager->flush();

            $notification = (new TemplatedEmail())
                ->to($this->adminEmail)
                ->replyTo((string) $contactRequest->getEmail())
                ->subject(sprintf('[Contact] %s — %s', $contactRequest->getName(), $contactRequest->getSubject()))
                ->htmlTemplate('email/contact_notification.html.twig')
                ->context([
                    'name' => $contactRequest->getName(),
                    'contactEmail' => $contactRequest->getEmail(),
                    'company' => $contactRequest->getCompany(),
                    'subject' => $contactRequest->getSubject(),
                    'contactMessage' => $contactRequest->getMessage(),
                    'receivedAt' => new \DateTimeImmutable(),
                ]);

            $this->mailer->send($notification);

            $this->addFlash('success', 'Merci, ton message a bien été envoyé. Je reviendrai vers toi rapidement.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('page/contact.html.twig', [
            'form' => $form,
            'contactLinks' => $this->withDefaultNetworkLinks(
                $this->formatProfileLinks(
                    $profileLinkRepository->findPublishedByCategory(LinkCategory::Network),
                    true
                )
            ),
        ]);
    }

    /**
     * @param list<ProfileLink> $links
     * @return list<array{
     *     label: string,
     *     title: string,
     *     subtitle: ?string,
     *     url: string,
     *     description: string,
     *     badge: string,
     *     theme: string,
     *     kicker: string,
     *     category: string,
     *     year: ?string
     * }>
     */
    private function formatProfileLinks(array $links, bool $onlyWithUrl = false): array
    {
        $cards = [];

        foreach ($links as $link) {
            $url = $link->getUrl() ?? '';
            if ($onlyWithUrl && '' === trim($url)) {
                continue;
            }

            $cards[] = [
                'label' => $link->getTitle(),
                'title' => $link->getTitle(),
                'subtitle' => $link->getSubtitle(),
                'url' => $url,
                'description' => $link->getDescription() ?? '',
                'badge' => $link->getBadge() ?? strtoupper(substr($link->getTitle(), 0, 2)),
                'theme' => $link->getTheme(),
                'kicker' => $link->getCategory()->label(),
                'category' => $link->getCategory()->value,
                'year' => $link->getYear(),
            ];
        }

        return $cards;
    }

    /**
     * @param list<array{
     *     label: string,
     *     title: string,
     *     subtitle: ?string,
     *     url: string,
     *     description: string,
     *     badge: string,
     *     theme: string,
     *     kicker: string,
     *     category: string,
     *     year: ?string
     * }> $links
     * @return list<array{
     *     label: string,
     *     title: string,
     *     subtitle: ?string,
     *     url: string,
     *     description: string,
     *     badge: string,
     *     theme: string,
     *     kicker: string,
     *     category: string,
     *     year: ?string
     * }>
     */
    private function withDefaultNetworkLinks(array $links, ?int $limit = null): array
    {
        $existingThemes = array_column($links, 'theme');

        foreach (self::DEFAULT_NETWORK_LINKS as $defaultLink) {
            if (!in_array($defaultLink['theme'], $existingThemes, true)) {
                $links[] = $defaultLink;
            }
        }

        if (null !== $limit) {
            return array_slice($links, 0, $limit);
        }

        return $links;
    }
}
