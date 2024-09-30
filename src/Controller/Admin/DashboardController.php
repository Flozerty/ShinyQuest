<?php

namespace App\Controller\Admin;

use App\Entity\Amis;
use App\Entity\Capture;
use App\Entity\MethodeCapture;
use App\Entity\Post;
use App\Entity\Sujet;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ShinyQuest')
        ;
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if (!$user instanceof User) {
            throw new \Exception("Wrong User");
        }

        return parent::configureUserMenu($user)
            ->setAvatarUrl("img/avatars/" . $user->getAvatar())
            ->setMenuItems([
                MenuItem::linkToUrl('Profil', '', 'my_profile')
            ])
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Dresseurs', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Methodes de capture', 'fa-solid fa-filter', MethodeCapture::class);
        yield MenuItem::linkToCrud('Captures', 'fa-brands fa-optin-monster', Capture::class);
        yield MenuItem::linkToCrud('Amis', 'fa-solid fa-hand-holding-heart', Amis::class);
        yield MenuItem::linkToCrud('Sujet', 'fa-solid fa-hand-holding-heart', Sujet::class);
        yield MenuItem::linkToCrud('Post', 'fa-solid fa-hand-holding-heart', Post::class);
        yield MenuItem::linkToUrl('Retour sur ShinyQuest', 'fa-solid fa-house-chimney', $this->generateUrl('app_home'));
    }
}