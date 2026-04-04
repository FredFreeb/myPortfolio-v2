<?php

namespace App\DataFixtures;

use App\Entity\ProjectUpdate;
use App\Entity\User;
use App\Entity\Work;
use App\Enum\ProjectAudience;
use App\Repository\ProjectUpdateRepository;
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
        private readonly UserPasswordHasherInterface $passwordHasher,
        #[Autowire('%env(string:ADMIN_EMAIL)%')]
        private readonly string $adminEmail,
        #[Autowire('%env(string:ADMIN_PASSWORD)%')]
        private readonly string $adminPassword,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->seedAdmin($manager);
        $this->seedWorks($manager);
        $this->seedProjectUpdates($manager);

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
}
