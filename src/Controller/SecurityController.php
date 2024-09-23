<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\CaptureRepository;
use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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

        // vérification honey pot
        if (isset($_POST["first_name"]) || !empty($_POST["first_name"])) {
            return $this->redirectToRoute('bot_detected');
        }

        // vérifie que l'utilisateur remplit bien le formulaire depuis le site. 
        if (isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], '127.0.0.1:8000')) {

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // suppression d'utilisateur
    #[Route('/user/delete', name: 'delete_account')]
    public function deleteAccount(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, SessionInterface $session, CaptureRepository $captureRepository, ResetPasswordRequestRepository $resetPasswordRequestRepository): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($user) {
            /////////// remplacement des PJ contenant des captures de l'user ///////////
            $fakeCapture = $captureRepository->findOneBy(["id" => 1]);

            $captures = $user->getCaptures();
            // récupération des captures
            foreach ($captures as $capture) {
                $messages = $capture->getMessages();
                // récupération des messages avec la capture en PJ (pas forcément dans une conversation de l'user!)
                foreach ($messages as $message) {
                    $message->setPj($fakeCapture);
                }
            }

            /////////// suppression des requests de reset password ///////////
            $requests = $resetPasswordRequestRepository->findBy(["user" => $this->getUser()]);
            foreach ($requests as $request) {
                $entityManager->remove($request);
                $entityManager->flush();
            }

            /////////// suppression de l'user ///////////

            // suppression de token d'auth & des données d'user dans la session
            $tokenStorage->setToken(null);
            $session->invalidate();

            $entityManager->remove($user);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        } else {
            return $this->redirectToRoute("error403");
        }
    }

    // reset password
    #[Route('/user/editPassword', name: 'change_password')]
    public function changePassword(): Response
    {

        return $this->redirectToRoute("my_profile");
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

    #[Route(path: '/bot', name: 'bot_detected')]
    public function botDetected(): Response
    {
        return $this->render('security/bot.html.twig', [
        ]);
    }
}
