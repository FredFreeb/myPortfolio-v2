<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\ProfileLink;
use App\Entity\ProjectUpdate;
use App\Entity\Testimonial;
use App\Entity\Training;
use App\Entity\User;
use App\Entity\Work;
use App\Enum\LinkCategory;
use App\Enum\ProjectAudience;
use App\Repository\ExperienceRepository;
use App\Repository\ProfileLinkRepository;
use App\Repository\ProjectUpdateRepository;
use App\Repository\TestimonialRepository;
use App\Repository\TrainingRepository;
use App\Repository\UserRepository;
use App\Repository\WorkRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly WorkRepository $workRepository,
        private readonly ProjectUpdateRepository $projectUpdateRepository,
        private readonly ExperienceRepository $experienceRepository,
        private readonly TrainingRepository $trainingRepository,
        private readonly TestimonialRepository $testimonialRepository,
        private readonly ProfileLinkRepository $profileLinkRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        #[Autowire('%env(string:ADMIN_EMAIL)%')]
        private readonly string $adminEmail,
        #[Autowire('%env(string:ADMIN_PASSWORD)%')]
        private readonly string $adminPassword,
        #[Autowire('%env(string:APP_ALBUM_URL)%')]
        private readonly string $albumUrl,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->seedAdmin($manager);
        $this->seedWorks($manager);
        $this->seedProjectUpdates($manager);
        $this->seedExperiences($manager);
        $this->seedTrainings($manager);
        $this->seedTestimonials($manager);
        $this->seedProfileLinks($manager);

        $manager->flush();
    }

    private function seedAdmin(ObjectManager $manager): void
    {
        $email = strtolower(trim($this->adminEmail));

        if ('' === $email) {
            return;
        }

        $user = $this->userRepository->findOneBy(['email' => $email]) ?? new User();
        $user
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword($user, $this->adminPassword));

        $manager->persist($user);
    }

    private function seedWorks(ObjectManager $manager): void
    {
        if ($this->workRepository->count([]) > 0) {
            return;
        }

        $works = [
            [
                'title' => 'Sound Of Memories',
                'clientName' => 'Projet personnel',
                'roleLabel' => 'Conception, front-end, narration visuelle',
                'stackSummary' => 'HTML, CSS, Sass, JavaScript, APIs Spotify et YouTube',
                'excerpt' => 'Une expérience musicale pensée comme un objet de mémoire sensible et interactif.',
                'description' => 'Ce projet mêle direction artistique, intégration et micro-interactions pour faire dialoguer mémoire, musique et navigation. Il m’a permis d’affirmer une approche où la technique soutient la sensibilité du récit.',
                'projectUrl' => 'https://fredfreeb.github.io/SoundOfMemories/',
                'repositoryUrl' => 'https://github.com/FredFreeb',
                'toolsUsed' => 'Figma, Photoshop, APIs Spotify/YouTube',
                'isFeatured' => true,
                'sortOrder' => 10,
            ],
            [
                'title' => 'Portfolio v2',
                'clientName' => 'Projet personnel',
                'roleLabel' => 'Refonte et design system',
                'stackSummary' => 'HTML, Sass, Bootstrap, JavaScript',
                'excerpt' => 'Le terrain d’essai initial qui m’a aidé à formaliser ma présence en ligne.',
                'description' => 'Cette base a servi de laboratoire visuel pour tester différentes mises en page, hiérarchies et approches de présentation des projets. La version Symfony actuelle en reprend l’intention tout en gagnant en maintenabilité.',
                'projectUrl' => 'https://github.com/FredFreeb/myPortfolio-v2',
                'repositoryUrl' => 'https://github.com/FredFreeb/myPortfolio-v2',
                'toolsUsed' => 'Bootstrap, Sass, GSAP',
                'isFeatured' => true,
                'sortOrder' => 20,
            ],
            [
                'title' => 'Togglez',
                'clientName' => 'Expérimentation',
                'roleLabel' => 'Prototype front-end',
                'stackSummary' => 'HTML, CSS, JavaScript',
                'excerpt' => 'Une expérimentation simple autour du mouvement, de l’état visuel et du feedback.',
                'description' => 'Un mini projet court, utile pour travailler la qualité de l’interaction et la lisibilité d’une interface sans infrastructure lourde.',
                'projectUrl' => 'https://fredfreeb.github.io/Togglez/',
                'repositoryUrl' => 'https://github.com/FredFreeb',
                'toolsUsed' => 'Vanilla JS, CSS transitions',
                'isFeatured' => false,
                'sortOrder' => 30,
            ],
            [
                'title' => 'Exercice AFPA',
                'clientName' => 'Formation',
                'roleLabel' => 'Intégration et structuration',
                'stackSummary' => 'HTML, CSS, Sass',
                'excerpt' => 'Une réalisation de formation qui m’a aidé à poser des fondations solides côté intégration.',
                'description' => 'Ce travail a consolidé mes réflexes autour de la structure HTML, du responsive et d’une organisation CSS plus rigoureuse.',
                'projectUrl' => 'https://fredfreeb.github.io/RenduFinalAfpaFribel/html/index.html',
                'repositoryUrl' => 'https://github.com/FredFreeb',
                'toolsUsed' => 'Sass, BEM, responsive layout',
                'isFeatured' => false,
                'sortOrder' => 40,
            ],
        ];

        foreach ($works as $workData) {
            $work = (new Work())
                ->setTitle($workData['title'])
                ->setClientName($workData['clientName'])
                ->setRoleLabel($workData['roleLabel'])
                ->setStackSummary($workData['stackSummary'])
                ->setExcerpt($workData['excerpt'])
                ->setDescription($workData['description'])
                ->setProjectUrl($workData['projectUrl'])
                ->setRepositoryUrl($workData['repositoryUrl'])
                ->setToolsUsed($workData['toolsUsed'])
                ->setIsFeatured($workData['isFeatured'])
                ->setSortOrder($workData['sortOrder']);

            $manager->persist($work);
        }
    }

    private function seedProjectUpdates(ObjectManager $manager): void
    {
        if ($this->projectUpdateRepository->count([]) > 0) {
            return;
        }

        $updates = [
            [
                'audience' => ProjectAudience::Public,
                'title' => 'Un récit accessible du projet',
                'summary' => 'Présenter Civitalisme comme une boussole concrète pour relier attention citoyenne, culture et numérique.',
                'body' => "La page grand public doit rester simple, incarnée et accueillante. L’idée n’est pas de tout expliquer d’un coup, mais de rendre visible la promesse, le ton et les usages possibles du projet.\n\nChaque bloc peut ensuite être enrichi depuis l’admin au fil des avancées.",
                'statusLabel' => 'Narration en cours',
                'outcome' => 'Clarifier la vision sans jargon.',
                'isFeatured' => true,
                'sortOrder' => 10,
            ],
            [
                'audience' => ProjectAudience::Public,
                'title' => 'Des preuves par prototypes',
                'summary' => 'Montrer des cas d’usage, des ateliers, des formats éditoriaux ou des démonstrateurs.',
                'body' => "Plutôt qu’un manifeste abstrait, la page valorise des expériences tangibles : prototypes, maquettes, ateliers, capsules ou démonstrations. Cela rend le projet plus concret et plus partageable.",
                'statusLabel' => 'Prototypage',
                'outcome' => 'Créer de l’adhésion par l’exemple.',
                'isFeatured' => false,
                'sortOrder' => 20,
            ],
            [
                'audience' => ProjectAudience::Public,
                'title' => 'Une parole plus incarnée',
                'summary' => 'Laisser une place à la poésie, à l’intuition et au sensible dans la manière de raconter le numérique.',
                'body' => "Civitalisme n’est pas qu’un cadre conceptuel. C’est aussi une manière d’habiter le web autrement. La page assume donc un ton plus humain, plus respirant, avec une place pour l’émotion et la transmission.",
                'statusLabel' => 'Éditorial',
                'outcome' => 'Donner une identité mémorable au projet.',
                'isFeatured' => false,
                'sortOrder' => 30,
            ],
            [
                'audience' => ProjectAudience::Institutional,
                'title' => 'Un cadre lisible pour les partenaires',
                'summary' => 'Présenter les objectifs, livrables, formats d’intervention et bénéfices de manière structurée.',
                'body' => "La page institutionnelle adopte un langage plus stratégique : gouvernance, impact, méthode, capacité d’animation et potentiel de coopération. Elle prépare une discussion avec des collectivités, associations, tiers-lieux ou acteurs culturels.",
                'statusLabel' => 'Positionnement',
                'outcome' => 'Faciliter la prise de contact qualifiée.',
                'isFeatured' => true,
                'sortOrder' => 10,
            ],
            [
                'audience' => ProjectAudience::Institutional,
                'title' => 'Une méthode éditable dans le temps',
                'summary' => 'Pouvoir ajouter facilement des blocs d’avancement, d’expérimentation ou de partenariat.',
                'body' => "L’admin de ce site permet d’ajouter rapidement de nouveaux blocs, ce qui transforme la page institutionnelle en carnet de bord crédible et actualisable sans développement systématique.",
                'statusLabel' => 'Admin-ready',
                'outcome' => 'Rendre le projet vivant pour les interlocuteurs.',
                'isFeatured' => false,
                'sortOrder' => 20,
            ],
            [
                'audience' => ProjectAudience::Institutional,
                'title' => 'Un langage commun entre design et intérêt général',
                'summary' => 'Mettre en évidence la capacité du projet à relier UX, culture numérique et attention citoyenne.',
                'body' => "La page institutionnelle insiste sur la capacité de Civitalisme à produire des formats concrets : ateliers, accompagnements, dispositifs éditoriaux, médiations et expérimentations avec des publics variés.",
                'statusLabel' => 'Partenariats',
                'outcome' => 'Ouvrir des portes de collaboration.',
                'isFeatured' => false,
                'sortOrder' => 30,
            ],
        ];

        foreach ($updates as $updateData) {
            $update = (new ProjectUpdate())
                ->setAudience($updateData['audience'])
                ->setTitle($updateData['title'])
                ->setSummary($updateData['summary'])
                ->setBody($updateData['body'])
                ->setStatusLabel($updateData['statusLabel'])
                ->setOutcome($updateData['outcome'])
                ->setIsFeatured($updateData['isFeatured'])
                ->setSortOrder($updateData['sortOrder']);

            $manager->persist($update);
        }
    }

    private function seedExperiences(ObjectManager $manager): void
    {
        if ($this->experienceRepository->count([]) > 0) {
            return;
        }

        $items = [
            [
                'company' => 'Stage WordPress & Symfony',
                'role' => 'Développeur stagiaire',
                'period' => '09.2023 - 11.2023',
                'location' => 'Blois',
                'status' => 'Mission terrain',
                'theme' => 'stage',
                'logoMonogram' => 'WP',
                'summary' => 'Première séquence professionnelle plus frontale côté développement, avec intégration, structure Symfony et mise en pratique sur un vrai cadre de production.',
                'sortOrder' => 10,
            ],
            [
                'company' => 'ADP Prague',
                'role' => 'Payroll Specialist',
                'period' => '2024 - aujourd\'hui',
                'location' => 'Prague',
                'status' => 'Toujours en poste',
                'theme' => 'adp',
                'logoMonogram' => 'ADP',
                'summary' => 'Un cadre international, structuré et exigeant, qui nourrit mon sens du détail, de la fiabilité et des flux bien tenus.',
                'sortOrder' => 20,
            ],
            [
                'company' => 'Ciné Vendôme',
                'role' => 'Adjoint de direction',
                'period' => '2012 - 2022',
                'location' => 'Vendôme',
                'status' => '10 ans de terrain',
                'theme' => 'cinema',
                'logoPath' => 'images/about/companies/cine-vendome.jpg',
                'summary' => 'Une décennie au contact du public, de l\'organisation et du rythme réel d\'une structure vivante, avant le virage plus net vers le numérique.',
                'sortOrder' => 30,
            ],
            [
                'company' => 'Amazon',
                'role' => 'Agent logistique',
                'period' => '2011 - 2012',
                'location' => 'Saran',
                'status' => 'Opérations',
                'theme' => 'amazon',
                'logoPath' => 'images/about/companies/amazon.png',
                'summary' => 'Un environnement rapide, industrialisé et exigeant, qui m\'a appris la cadence, la précision et le sérieux opérationnel.',
                'sortOrder' => 40,
            ],
            [
                'company' => 'Le Calypso',
                'role' => 'Projectionniste',
                'period' => '2007 - 2010',
                'location' => 'Viry-Châtillon',
                'status' => 'Cabine & exploitation',
                'theme' => 'calypso',
                'logoPath' => 'images/about/companies/cinema-calypso.png',
                'summary' => 'Les premières responsabilités longues dans l\'exploitation cinéma, entre technique, autonomie et relation concrète au lieu.',
                'sortOrder' => 50,
            ],
        ];

        foreach ($items as $data) {
            $experience = (new Experience())
                ->setCompanyName($data['company'])
                ->setRole($data['role'])
                ->setPeriod($data['period'])
                ->setLocation($data['location'])
                ->setStatus($data['status'])
                ->setTheme($data['theme'])
                ->setSummary($data['summary'])
                ->setSortOrder($data['sortOrder']);

            if (isset($data['logoMonogram'])) {
                $experience->setMonogram($data['logoMonogram']);
            }
            if (isset($data['logoPath'])) {
                $experience->setLogoPath($data['logoPath']);
            }

            $manager->persist($experience);
        }
    }

    private function seedTrainings(ObjectManager $manager): void
    {
        if ($this->trainingRepository->count([]) > 0) {
            return;
        }

        $items = [
            [
                'school' => 'AFPA',
                'program' => 'Développeur Web & Web Mobile',
                'period' => '2023',
                'theme' => 'afpa',
                'imagePath' => 'images/about/training/afpa.png',
                'summary' => 'Le moment où la pratique s\'est structurée plus frontalement autour du code, du responsive, de l\'intégration et des bases full-stack.',
                'sortOrder' => 10,
            ],
            [
                'school' => 'INA',
                'program' => 'Responsable Technique de Salles',
                'period' => '2015',
                'theme' => 'ina',
                'imagePath' => 'images/about/training/ina.png',
                'summary' => 'Une formation orientée exploitation, technique de salle et compréhension des contraintes audiovisuelles en contexte professionnel.',
                'sortOrder' => 20,
            ],
            [
                'school' => 'AFOMAV',
                'program' => 'C.A.P Projectionniste',
                'period' => '2006 - 2007',
                'theme' => 'afomav',
                'imagePath' => 'images/about/training/afomav.jpg',
                'summary' => 'Le socle métier initial autour de la projection, de la cabine et de la rigueur technique appliquée.',
                'sortOrder' => 30,
            ],
        ];

        foreach ($items as $data) {
            $training = (new Training())
                ->setSchoolName($data['school'])
                ->setProgram($data['program'])
                ->setPeriod($data['period'])
                ->setTheme($data['theme'])
                ->setImagePath($data['imagePath'])
                ->setSummary($data['summary'])
                ->setSortOrder($data['sortOrder']);

            $manager->persist($training);
        }
    }

    private function seedTestimonials(ObjectManager $manager): void
    {
        if ($this->testimonialRepository->count([]) > 0) {
            return;
        }

        $items = [
            [
                'authorName' => 'Responsable technique',
                'authorRole' => 'Tuteur de stage',
                'company' => 'Stage WordPress & Symfony',
                'quote' => 'Frédéric a su s\'adapter rapidement à notre environnement technique et a montré une vraie curiosité pour les bonnes pratiques de développement.',
                'theme' => 'stage',
                'sortOrder' => 10,
            ],
            [
                'authorName' => 'Directeur de salle',
                'authorRole' => 'Direction',
                'company' => 'Ciné Vendôme',
                'quote' => 'Dix ans de collaboration fiable, avec un sens de l\'organisation et du public qui a structuré durablement le fonctionnement de la salle.',
                'theme' => 'cinema',
                'sortOrder' => 20,
            ],
            [
                'authorName' => 'Formateur référent',
                'authorRole' => 'Encadrement pédagogique',
                'company' => 'AFPA',
                'quote' => 'Un profil atypique et motivé, capable de relier ses expériences passées à une vraie logique de développement web structuré.',
                'theme' => 'afpa',
                'sortOrder' => 30,
            ],
        ];

        foreach ($items as $data) {
            $testimonial = (new Testimonial())
                ->setAuthorName($data['authorName'])
                ->setAuthorRole($data['authorRole'])
                ->setCompanyName($data['company'])
                ->setContent($data['quote'])
                ->setTheme($data['theme'])
                ->setSortOrder($data['sortOrder']);

            $manager->persist($testimonial);
        }
    }

    private function seedProfileLinks(ObjectManager $manager): void
    {
        if ($this->profileLinkRepository->count([]) > 0) {
            return;
        }

        $albumUrl = null;

        $items = [
            [
                'category' => LinkCategory::Network,
                'title' => 'Code public',
                'subtitle' => 'GitHub',
                'url' => 'https://github.com/FredFreeb',
                'description' => 'Mes bases, prototypes et explorations autour du web, de l\'UI et des projets personnels.',
                'badge' => 'GH',
                'theme' => 'github',
                'year' => '2026',
                'sortOrder' => 10,
            ],
            [
                'category' => LinkCategory::Network,
                'title' => 'Trajectoire pro',
                'subtitle' => 'LinkedIn',
                'url' => 'https://www.linkedin.com/in/FredFreeb',
                'description' => 'Une lecture plus professionnelle du parcours, des responsabilités et du positionnement actuel.',
                'badge' => 'in',
                'theme' => 'linkedin',
                'year' => '2026',
                'sortOrder' => 20,
            ],
            [
                'category' => LinkCategory::Hobby,
                'title' => 'Album et univers personnel',
                'subtitle' => 'Musique et écriture',
                'url' => null,
                'description' => 'Une autre facette de mon travail, plus sensible et musicale, qui nourrit aussi ma manière de concevoir le web.',
                'badge' => null,
                'theme' => 'music',
                'year' => null,
                'sortOrder' => 30,
            ],
        ];

        foreach ($items as $data) {
            $link = (new ProfileLink())
                ->setCategory($data['category'])
                ->setTitle($data['title'])
                ->setSubtitle($data['subtitle'])
                ->setUrl($data['url'])
                ->setDescription($data['description'])
                ->setBadge($data['badge'])
                ->setTheme($data['theme'])
                ->setYear($data['year'])
                ->setSortOrder($data['sortOrder']);

            $manager->persist($link);
        }
    }
}
