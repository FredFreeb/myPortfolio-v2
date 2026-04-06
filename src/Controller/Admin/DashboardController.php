<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/chezmoi/espace', routeName: 'admin_dashboard')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private readonly AdminUrlGenerator $adminUrlGenerator)
    {
    }

    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator
            ->setController(WorkCrudController::class)
            ->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Portfolio + Civitalisme')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Voir le site', 'fa fa-arrow-up-right-from-square', 'app_home');
        yield MenuItem::section('Contenus');
        yield MenuItem::linkTo(WorkCrudController::class, 'Travaux', 'fa fa-briefcase');
        yield MenuItem::linkTo(ProjectUpdateCrudController::class, 'Blocs Civitalisme', 'fa fa-landmark');
        yield MenuItem::section('Messages');
        yield MenuItem::linkTo(ContactMessageCrudController::class, 'Contacts', 'fa fa-envelope');
    }
}
