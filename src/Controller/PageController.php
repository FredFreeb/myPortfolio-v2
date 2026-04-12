<?php

namespace App\Controller;

use App\Content\CivitalismeContentProvider;
use App\Entity\ContactMessage;
use App\Enum\ProjectAudience;
use App\Form\ContactType;
use App\Form\Model\ContactRequest;
use App\Repository\ProjectUpdateRepository;
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
    public function home(WorkRepository $workRepository, ProjectUpdateRepository $projectUpdateRepository): Response
    {
        return $this->render('page/home.html.twig', [
            'featuredWorks' => $workRepository->findPublished(6),
            'publicHighlights' => $projectUpdateRepository->findPublishedByAudience(ProjectAudience::Public, 2),
            'institutionalHighlights' => $projectUpdateRepository->findPublishedByAudience(ProjectAudience::Institutional, 2),
            'contactLinks' => $this->contactLinks(),
        ]);
    }

    #[Route('/about', name: 'app_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('page/about.html.twig', [
            'journey' => [
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
            ],
            'environment' => [
                'Symfony 8, Twig, Doctrine et un admin sobre pour éditer sans friction.',
                'Docker et des commandes de démarrage lisibles pour retrouver un environnement stable rapidement.',
                'Une approche privacy-friendly : peu de dépendances externes, données utiles uniquement, sécurité native Symfony.',
            ],
            'passions' => [
                'IA générative et outils capables d’augmenter le travail humain sans noyer la clarté.',
                'Poésie, rythme, typographie et narration visuelle comme prolongement du code.',
                'Explorations musicales et projets personnels où le web devient un espace sensible.',
            ],
            'profileHighlights' => [
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
            ],
            'experienceTimeline' => [
                [
                    'company' => 'Stage WordPress & Symfony',
                    'role' => 'Développeur stagiaire',
                    'period' => '09.2023 - 11.2023',
                    'location' => 'Blois',
                    'status' => 'Mission terrain',
                    'theme' => 'stage',
                    'logoMonogram' => 'WP',
                    'summary' => 'Première séquence professionnelle plus frontale côté développement, avec intégration, structure Symfony et mise en pratique sur un vrai cadre de production.',
                ],
                [
                    'company' => 'ADP Prague',
                    'role' => 'Payroll Specialist',
                    'period' => '2024 - aujourd’hui',
                    'location' => 'Prague',
                    'status' => 'Toujours en poste',
                    'theme' => 'adp',
                    'logoMonogram' => 'ADP',
                    'summary' => 'Un cadre international, structuré et exigeant, qui nourrit mon sens du détail, de la fiabilité et des flux bien tenus.',
                ],
                [
                    'company' => 'Ciné Vendôme',
                    'role' => 'Adjoint de direction',
                    'period' => '2012 - 2022',
                    'location' => 'Vendôme',
                    'status' => '10 ans de terrain',
                    'theme' => 'cinema',
                    'logoPath' => 'images/about/companies/cine-vendome.jpg',
                    'summary' => 'Une décennie au contact du public, de l’organisation et du rythme réel d’une structure vivante, avant le virage plus net vers le numérique.',
                ],
                [
                    'company' => 'Amazon',
                    'role' => 'Agent logistique',
                    'period' => '2011 - 2012',
                    'location' => 'Saran',
                    'status' => 'Opérations',
                    'theme' => 'amazon',
                    'logoPath' => 'images/about/companies/amazon.png',
                    'summary' => 'Un environnement rapide, industrialisé et exigeant, qui m’a appris la cadence, la précision et le sérieux opérationnel.',
                ],
                [
                    'company' => 'Le Calypso',
                    'role' => 'Projectionniste',
                    'period' => '2007 - 2010',
                    'location' => 'Viry-Châtillon',
                    'status' => 'Cabine & exploitation',
                    'theme' => 'calypso',
                    'logoPath' => 'images/about/companies/cinema-calypso.png',
                    'summary' => 'Les premières responsabilités longues dans l’exploitation cinéma, entre technique, autonomie et relation concrète au lieu.',
                ],
            ],
            'trainingTimeline' => [
                [
                    'school' => 'AFPA',
                    'program' => 'Développeur Web & Web Mobile',
                    'period' => '2023',
                    'theme' => 'afpa',
                    'summary' => 'Le moment où la pratique s’est structurée plus frontalement autour du code, du responsive, de l’intégration et des bases full-stack.',
                    'imagePath' => 'images/about/training/afpa.png',
                ],
                [
                    'school' => 'INA',
                    'program' => 'Responsable Technique de Salles',
                    'period' => '2015',
                    'theme' => 'ina',
                    'summary' => 'Une formation orientée exploitation, technique de salle et compréhension des contraintes audiovisuelles en contexte professionnel.',
                    'imagePath' => 'images/about/training/ina.png',
                ],
                [
                    'school' => 'AFOMAV',
                    'program' => 'C.A.P Projectionniste',
                    'period' => '2006 - 2007',
                    'theme' => 'afomav',
                    'summary' => 'Le socle métier initial autour de la projection, de la cabine et de la rigueur technique appliquée.',
                    'imagePath' => 'images/about/training/afomav.jpg',
                ],
            ],
            'companyLogos' => [
                [
                    'name' => 'ADP Prague',
                    'theme' => 'adp',
                    'monogram' => 'ADP',
                ],
                [
                    'name' => 'Ciné Vendôme',
                    'imagePath' => 'images/about/companies/cine-vendome.jpg',
                    'theme' => 'cinema',
                ],
                [
                    'name' => 'BUT',
                    'imagePath' => 'images/about/companies/but.svg',
                    'theme' => 'but',
                ],
                [
                    'name' => 'Amazon',
                    'imagePath' => 'images/about/companies/amazon.png',
                    'theme' => 'amazon',
                ],
                [
                    'name' => 'Quick',
                    'imagePath' => 'images/about/companies/quick.png',
                    'theme' => 'quick',
                ],
                [
                    'name' => 'Cinéma Le Calypso',
                    'imagePath' => 'images/about/companies/cinema-calypso.png',
                    'theme' => 'calypso',
                ],
                [
                    'name' => 'Exofarm',
                    'imagePath' => 'images/about/companies/exofarm.png',
                    'theme' => 'exofarm',
                ],
            ],
            'trainingLogos' => [
                [
                    'name' => 'AFPA',
                    'imagePath' => 'images/about/training/afpa.png',
                    'theme' => 'afpa',
                ],
                [
                    'name' => 'INA',
                    'imagePath' => 'images/about/training/ina.png',
                    'theme' => 'ina',
                ],
                [
                    'name' => 'AFOMAV',
                    'imagePath' => 'images/about/training/afomav.jpg',
                    'theme' => 'afomav',
                ],
            ],
            'networkCards' => [
                [
                    'title' => 'Code public',
                    'kicker' => 'Code source',
                    'badge' => 'GH',
                    'theme' => 'github',
                    'url' => 'https://github.com/FredFreeb',
                    'description' => 'Mes bases, prototypes et explorations autour du web, de l’UI et des projets personnels.',
                ],
                [
                    'title' => 'Trajectoire pro',
                    'kicker' => 'Parcours public',
                    'badge' => 'in',
                    'theme' => 'linkedin',
                    'url' => 'https://www.linkedin.com/in/FredFreeb',
                    'description' => 'Une lecture plus professionnelle du parcours, des responsabilités et du positionnement actuel.',
                ],
            ],
            'albumUrl' => $this->albumUrl,
            'contactLinks' => $this->contactLinks(),
        ]);
    }

    #[Route('/civitalisme', name: 'app_civitalisme', methods: ['GET'])]
    public function civitalisme(
        ProjectUpdateRepository $projectUpdateRepository,
        CivitalismeContentProvider $contentProvider,
    ): Response
    {
        return $this->render('page/civitalisme/index.html.twig', [
            'content' => $contentProvider->publicPage(),
            'publicHighlights' => $projectUpdateRepository->findPublishedByAudience(ProjectAudience::Public, 3),
            'civitalismeVideoUrl' => $this->civitalismeVideoUrl,
        ]);
    }

    #[Route('/civitalisme/cadre-institutionnel', name: 'app_civitalisme_technical', methods: ['GET'])]
    public function civitalismeTechnical(
        ProjectUpdateRepository $projectUpdateRepository,
        CivitalismeContentProvider $contentProvider,
    ): Response
    {
        return $this->render('page/civitalisme/technical.html.twig', [
            'content' => $contentProvider->technicalPage(),
            'institutionalHighlights' => $projectUpdateRepository->findPublishedByAudience(ProjectAudience::Institutional, 4),
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
    public function legalNotice(): Response
    {
        return $this->render('page/legal/mentions.html.twig', [
            'editor' => [
                'name' => 'Frederic Fribel',
                'role' => 'Editeur et responsable de publication',
                'site' => 'Portfolio + Civitalisme',
            ],
            'contactLinks' => $this->contactLinks(),
            'hosting' => [
                'name' => 'Infrastructure VPS privee',
                'body' => 'Le site est concu pour etre deploye sur un VPS ou un serveur dedie. Les informations completes d hebergement doivent etre renseignees avant une mise en production publique ouverte.',
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
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
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
                    'email' => $contactRequest->getEmail(),
                    'company' => $contactRequest->getCompany(),
                    'subject' => $contactRequest->getSubject(),
                    'message' => $contactRequest->getMessage(),
                    'receivedAt' => new \DateTimeImmutable(),
                ]);

            $this->mailer->send($notification);

            $this->addFlash('success', 'Merci, ton message a bien été envoyé. Je reviendrai vers toi rapidement.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('page/contact.html.twig', [
            'form' => $form,
            'contactLinks' => $this->contactLinks(),
        ]);
    }

    /**
     * @return list<array{label: string, url: string, description: string, badge: string, theme: string}>
     */
    private function contactLinks(): array
    {
        return [
            [
                'label' => 'GitHub',
                'url' => 'https://github.com/FredFreeb',
                'description' => 'Voir le code et les expérimentations.',
                'badge' => 'GH',
                'theme' => 'github',
            ],
            [
                'label' => 'LinkedIn',
                'url' => 'https://www.linkedin.com/in/FredFreeb',
                'description' => 'Échanger sur une mission, un projet ou une collaboration.',
                'badge' => 'in',
                'theme' => 'linkedin',
            ],
        ];
    }
}
