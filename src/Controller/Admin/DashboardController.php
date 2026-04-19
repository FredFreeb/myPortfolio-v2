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
        yield MenuItem::linkToRoute('Travaux', 'fa fa-briefcase', 'admin_dashboard_work_index');
        yield MenuItem::linkToRoute('Expériences', 'fa fa-building', 'admin_dashboard_experience_index');
        yield MenuItem::linkToRoute('Formations', 'fa fa-graduation-cap', 'admin_dashboard_training_index');
        yield MenuItem::linkToRoute('Témoignages', 'fa fa-quote-left', 'admin_dashboard_testimonial_index');
        yield MenuItem::linkToRoute('Liens profil', 'fa fa-link', 'admin_dashboard_profile_link_index');
        yield MenuItem::linkToRoute('Blocs Civitalisme', 'fa fa-landmark', 'admin_dashboard_project_update_index');
        yield MenuItem::section('Messages');
        yield MenuItem::linkToRoute('Contacts', 'fa fa-envelope', 'admin_dashboard_contact_message_index');
        yield MenuItem::section('Internationalisation');
        yield MenuItem::linkToRoute('Traductions', 'fa fa-language', 'admin_translations_index');
    }
}
