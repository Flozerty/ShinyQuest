<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\User;
use App\Form\CaptureType;
use App\Form\ShasseStartType;
use App\HttpClient\ApiHttpClient;
use App\Repository\CaptureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CaptureController extends AbstractController
{

  // Toutes mes captures
  #[Route('/{pseudo}/captures', name: 'captures')]
  public function captures(User $user, CaptureRepository $captureRepository): Response
  {
    if (!$user) {
      return $this->redirectToRoute('app_login');
    }

    $captures = $captureRepository->findBy(['user' => $user, 'termine' => 1], ['dateCapture' => "DESC"]);

    return $this->render('capture/captures.html.twig', [
      "page_title" => $this->getUser() == $user ? 'Mes captures' : "Captures de " . $user->getPseudo(),
      "captures" => $captures,
      "user" => $user,
    ]);
  }

  // Shasses en cours
  #[Route('/shasses', name: 'my_shasses')]
  public function shasses(ApiHttpClient $apiHttpClient, CaptureRepository $captureRepository, Request $request, EntityManagerInterface $entityManager): Response
  {
    $user = $this->getUser();
    if (!$user) {
      return $this->redirectToRoute('app_login');
    }

    $shasse = new Capture;

    //////////////////////////  formulaire de shasse terminée //////////////////////////
    $formCapture = $this->createForm(CaptureType::class, $shasse);
    $formCapture->handleRequest($request);

    // validation du formulaire de shiny trouvé
    if ($formCapture->isSubmitted() && $formCapture->isValid()) {

      $idCapture = filter_input(INPUT_POST, "IdCapture", FILTER_VALIDATE_INT);
      $ball = filter_input(INPUT_POST, "ball", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // on va récupérer la capture avec cet ID.
      $capture = $captureRepository->find($idCapture);

      // et on lui donne les résultats du formulaire de modal :
      $capture->setSurnom($shasse->getSurnom());
      $capture->setDateCapture($shasse->getDateCapture());
      $capture->setSexe($shasse->getSexe());

      $capture->setBall($ball);

      $capture->setSuivi(false);
      $capture->setTermine(true);

      $entityManager->flush();


      $this->addFlash('success', 'Félicitations, vous avez capturé '.$capture->getnomPokemon());
    }

    $captures = $captureRepository->findBy(['user' => $user, 'termine' => 0]);

    return $this->render('capture/shasses.html.twig', [
      "page_title" => 'Mes shasses',
      "captures" => $captures,
      "formCapture" => $formCapture,
      "allPokemons" => [],
      "balls" => [],
    ]);
  }

  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////

  #[Route('/shasse/new', name: 'new_shasse_form', methods: ['GET'])]
  public function newShasseForm(Request $request, ApiHttpClient $apiHttpClient): JsonResponse
  {
    $user = $this->getUser();
    if (!$user) {
      return new JsonResponse(['success' => false], 403);
    }

    // Récupération des jeux et des pokémons
    $games = $apiHttpClient->getAllGamesVersions();
    $allPokemons = $apiHttpClient->getAllPokemons();

    $shasse = new Capture();
    $formNewShasse = $this->createForm(ShasseStartType::class, $shasse, [
      'games' => $games,
    ]);

    // Rendre uniquement le formulaire
    $formHtml = $this->renderView('capture/form_new_shasse.html.twig', [
      'formNewShasse' => $formNewShasse->createView(),
      'allPokemons' => $allPokemons,
    ]);

    return new JsonResponse(['html' => $formHtml]);
  }

  // supprimer une shasses
  #[Route('/shasse/{id}/delete', name: 'delete_shasse')]
  public function deleteShasse(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $entityManager->remove($shasse);
      $entityManager->flush();
    }

    return $this->redirectToRoute("my_shasses");
  }

  // supprimer une capture
  #[Route('/capture/{id}/delete', name: 'delete_capture')]
  public function deleteCapture(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $entityManager->remove($shasse);
      $entityManager->flush();
    }

    return $this->redirectToRoute("my_captures");
  }

  // retirer du suivi des shasses
  #[Route('/shasse/{id}/stop_suivi', name: 'stop_suivi')]
  public function stopSuivi(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $shasse->setSuivi(false);
      $entityManager->flush();
    }

    return $this->redirectToRoute("my_shasses");
  }

  // ajouter au suivi des shasses
  #[Route('/shasse/{id}/add_suivi', name: 'add_suivi')]
  public function addSuivi(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $shasse->setSuivi(true);
      $entityManager->flush();
    }
    return $this->redirectToRoute("my_shasses");
  }

  // +1 button
  #[Route('/shasse/{id}/increment', name: 'increment_shasse')]
  public function incrementCounter(int $id, EntityManagerInterface $entityManager, CaptureRepository $captureRepository, Request $request): Response
  {
    $shasse = $captureRepository->find($id);

    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $shasse->setNbRencontres($shasse->getNbRencontres() + 1);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }

  // -1 button
  #[Route('/shasse/{id}/decrement', name: 'decrement_shasse')]
  public function decrementCounter(int $id, EntityManagerInterface $entityManager, CaptureRepository $captureRepository, Request $request): Response
  {
    $shasse = $captureRepository->find($id);

    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $shasse->setNbRencontres($shasse->getNbRencontres() - 1);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }

  // +10 button
  #[Route('/shasse/{id}/increment10', name: 'increment10_shasse')]
  public function increment10Counter(int $id, EntityManagerInterface $entityManager, CaptureRepository $captureRepository, Request $request): Response
  {
    $shasse = $captureRepository->find($id);

    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $shasse->setNbRencontres($shasse->getNbRencontres() + 10);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }

  // -10 button
  #[Route('/shasse/{id}/decrement10', name: 'decrement10_shasse')]
  public function decrement10Counter(int $id, EntityManagerInterface $entityManager, CaptureRepository $captureRepository, Request $request): Response
  {
    $shasse = $captureRepository->find($id);

    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $shasse->setNbRencontres($shasse->getNbRencontres() - 10);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }

  // update counter manually on the input
  #[Route('/shasse/{id}/updateCounter', name: 'shasse_updateCounter')]
  public function updateCounter(int $id, EntityManagerInterface $entityManager, CaptureRepository $captureRepository, Request $request): Response
  {
    $shasse = $captureRepository->find($id);
    $newValue = $request->request->get('nbRencontres');

    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $shasse->setNbRencontres($newValue);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }
}
