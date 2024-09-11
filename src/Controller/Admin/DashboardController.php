<?php

namespace App\Controller\Admin;

use App\Entity\Amis;
use App\Entity\Capture;
use App\Entity\MethodeCapture;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(CaptureCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ShinyQuest')
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Dresseurs', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Methodes de capture', 'fas fa-list', MethodeCapture::class);
        yield MenuItem::linkToCrud('Captures', 'fas fa-list', Capture::class);
        yield MenuItem::linkToCrud('Amis', 'fas fa-list', Amis::class);

    }
}