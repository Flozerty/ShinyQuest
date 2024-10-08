<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\CaptureRepository;
use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\TwitchClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // connexion
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
    // connexion avec Twitch
    #[Route(path: '/connect/twitch_helix', name: 'twitch_helix_connect')]
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        // dd($clientRegistry->getClient('twitch_helix'));

        $client = $clientRegistry->getClient('twitch_helix');
        return $client->redirect(['user:read:email'], [
            'redirect_uri' => 'https://127.0.0.1:8000/oauth/check/twitch_helix'
        ]);
    }

    // Gestion du retour après l'authentification sur Twitch
    #[Route(path: '/oauth/check/twitch_helix', name: 'oauth_check_twitch_helix')]
    public function check(Request $request, ClientRegistry $clientRegistry)
    {
        $client = $clientRegistry->getClient('twitch_helix');

        try {
            // Récupère les informations de l'utilisateur Twitch authentifié
            $user = $client->fetchUser();
            dd($user);

            // Ici, tu peux gérer l'utilisateur (par exemple, le sauvegarder dans la base de données, créer une session, etc.)
            return new Response('Connexion réussie avec Twitch ! Utilisateur : ' . $user->getNickname());
        } catch (\Exception $e) {
            return new Response('Erreur lors de la connexion avec Twitch : ' . $e->getMessage());
        }
    }

    // déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // suppression d'utilisateur
    #[Route('/user/delete', name: 'delete_account')]
    public function deleteAccount(Request $request, CsrfTokenManagerInterface $csrfTokenManager, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, SessionInterface $session, CaptureRepository $captureRepository, ResetPasswordRequestRepository $resetPasswordRequestRepository): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        // vérification du token CSRF
        $token = new CsrfToken('delete_item', $request->request->get('_token'));
    
        if (!$csrfTokenManager->isTokenValid($token)) {
            $this->redirectToRoute('error403');
        }

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
