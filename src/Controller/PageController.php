<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Enum\ProjectAudience;
use App\Form\ContactType;
use App\Form\Model\ContactRequest;
use App\Repository\ProjectUpdateRepository;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    public function __construct(
        #[Autowire('%env(string:APP_ALBUM_URL)%')]
        private readonly string $albumUrl,
        #[Autowire(service: 'limiter.contact_form')]
        private readonly RateLimiterFactory $contactFormLimiter,
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
            'albumUrl' => $this->albumUrl,
            'contactLinks' => $this->contactLinks(),
        ]);
    }

    #[Route('/civitalisme', name: 'app_civitalisme', methods: ['GET'])]
    public function civitalisme(ProjectUpdateRepository $projectUpdateRepository): Response
    {
        return $this->render('page/civitalisme/index.html.twig', [
            'publicHighlights' => $projectUpdateRepository->findPublishedByAudience(ProjectAudience::Public, 3),
            'institutionalHighlights' => $projectUpdateRepository->findPublishedByAudience(ProjectAudience::Institutional, 3),
        ]);
    }

    #[Route('/civitalisme/{audience}', name: 'app_civitalisme_audience', requirements: ['audience' => 'grand-public|institutionnel'], methods: ['GET'])]
    public function civitalismeAudience(string $audience, ProjectUpdateRepository $projectUpdateRepository): Response
    {
        $projectAudience = ProjectAudience::tryFrom($audience);

        if (!$projectAudience instanceof ProjectAudience) {
            throw $this->createNotFoundException();
        }

        return $this->render('page/civitalisme/audience.html.twig', [
            'audience' => $projectAudience,
            'updates' => $projectUpdateRepository->findPublishedByAudience($projectAudience),
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
                strtolower((string) $contactRequest->email)
            );
            $rateLimit = $this->contactFormLimiter->create($limiterKey)->consume(1);

            if (!$rateLimit->isAccepted()) {
                $this->addFlash('error', 'Le formulaire a été envoyé trop souvent. Merci de patienter quelques minutes.');

                return $this->redirectToRoute('app_contact');
            }

            if (!empty($contactRequest->website)) {
                $this->addFlash('success', 'Merci, ton message a bien été pris en compte.');

                return $this->redirectToRoute('app_contact');
            }

            $message = (new ContactMessage())
                ->setName((string) $contactRequest->name)
                ->setEmail((string) $contactRequest->email)
                ->setCompany($contactRequest->company)
                ->setSubject((string) $contactRequest->subject)
                ->setMessage((string) $contactRequest->message);

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Merci, ton message a bien été envoyé. Je reviendrai vers toi rapidement.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('page/contact.html.twig', [
            'form' => $form,
            'contactLinks' => $this->contactLinks(),
        ]);
    }

    /**
     * @return list<array{label: string, url: string, description: string}>
     */
    private function contactLinks(): array
    {
        return [
            [
                'label' => 'GitHub',
                'url' => 'https://github.com/FredFreeb',
                'description' => 'Voir le code et les expérimentations.',
            ],
            [
                'label' => 'LinkedIn',
                'url' => 'https://www.linkedin.com/in/FredFreeb',
                'description' => 'Échanger sur une mission, un projet ou une collaboration.',
            ],
        ];
    }
}
