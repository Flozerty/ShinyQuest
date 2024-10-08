<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
  public function __construct(private EmailVerifier $emailVerifier)
  {
  }

  #[Route('/register', name: 'app_register')]
  public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
  {
    if ($this->getUser()) {
      $this->addFlash("error", "Vous êtes déjà connecté.");
      return $this->redirectToRoute('app_home');
    }
    $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      // vérifie que l'utilisateur remplit bien le formulaire depuis le site. 
      if (isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], '127.0.0.1:8000')) {
        // vérification honey pot
        if (isset($_POST["first_name"]) && empty($_POST["first_name"])) {

          // encode the plain password
          $user->setPassword(
            $userPasswordHasher->hashPassword(
              $user,
              $form->get('plainPassword')->getData()
            )
          );

          $entityManager->persist($user);
          $entityManager->flush();

          // generate a signed url and email it to the user
          $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
              ->from(new Address('admin@shinyquest.com', 'Admin ShinyQuest'))
              ->to($user->getEmail())
              ->subject('Veuillez confirmer votre email')
              ->htmlTemplate('registration/confirmation_email.html.twig')
          );

          // do anything else you need here, like send an email

          return $security->login($user, AppAuthenticator::class, 'main');
        } else {
          return $this->redirectToRoute('bot_detected');
        }
      } else {
        return $this->redirectToRoute('bot_detected');
      }
    }

    return $this->render('registration/register.html.twig', [
      'registrationForm' => $form,
    ]);
  }

  #[Route('/verify/email', name: 'app_verify_email')]
  public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
  {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    // validate email confirmation link, sets User::isVerified=true and persists
    try {
      $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
    } catch (VerifyEmailExceptionInterface $exception) {
      $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

      return $this->redirectToRoute('app_register');
    }

    // @TODO Change the redirect on success and handle or remove the flash message in your templates
    $this->addFlash('success', 'Votre adressse email a été vérifiée.');

    return $this->redirectToRoute('app_home');
  }
}
