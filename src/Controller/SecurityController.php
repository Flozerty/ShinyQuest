<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si déjà connecté -> home
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/user/delete', name: 'delete_account')]
    public function deleteAccount(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user) {
            // $user = $entityManager->merge($user);
            $entityManager->remove($user);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        } else {
            dd("test");
        }
    }

    //////////////////////////////////////////////////////
    /////////////////////// ERRORS ///////////////////////
    //////////////////////////////////////////////////////

    #[Route(path: '/error400', name: 'error400')]
    public function error400(): Response
    {
        return $this->render('security/error.html.twig', [
            "errorCode" => "400",
        ]);
    }

    #[Route(path: '/error403', name: 'error403')]
    public function error403(): Response
    {
        return $this->render('security/error.html.twig', [
            "errorCode" => "403",
        ]);
    }

    #[Route(path: '/error404', name: 'error404')]
    public function error404(): Response
    {
        return $this->render('security/error.html.twig', [
            "errorCode" => "404",
        ]);
    }

    #[Route(path: '/error500', name: 'error500')]
    public function error500(): Response
    {
        return $this->render('security/error.html.twig', [
            "errorCode" => "500",
        ]);
    }
}
