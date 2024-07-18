<?php

namespace App\Controller;

use App\Entity\Capture;
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
  #[Route('/captures', name: 'my_captures')]
  public function captures(CaptureRepository $captureRepository): Response
  {

    $user = $this->getUser();
    if (!$user) {
      return $this->redirectToRoute('app_login');
    }

    $captures = $captureRepository->findBy(['user' => $user, 'termine' => 1], ['dateCapture' => "DESC"]);

    return $this->render('capture/captures.html.twig', [
      "page_title" => 'Mes captures',
      "captures" => $captures,
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

    // récupération de tous les jeux
    $games = $apiHttpClient->getAllGamesVersions();
    $shasse = new Capture;

    ///////////////////  formulaire de création de nouvelle shasse ///////////////////
    $formNewShasse = $this->createForm(ShasseStartType::class, $shasse, [
      /* jeux en paramètres pour le formType */
      'games' => $games,
    ]);

    $formNewShasse->handleRequest($request);

    // validation du formulaire de nouvelle shasse
    if ($formNewShasse->isSubmitted() && $formNewShasse->isValid()) {

      $pokemonId = filter_input(INPUT_POST, "pokemonId", FILTER_VALIDATE_INT);

      // on récupère le nom du pokemon, son sprite, etc.
      $pokemon = $apiHttpClient->getPokemonInfos($pokemonId);
      $nomPokemon = $apiHttpClient->getPokemonNameById($pokemonId);

      $shasse = $formNewShasse->getData(); //filter tous les inputs

      $shasse->setFavori(false);
      $shasse->setSuivi(false);
      $shasse->setTermine(false);
      $shasse->setPokedexId($pokemonId);
      $shasse->setImgShiny($pokemon["sprites"]["other"]["official-artwork"]["front_shiny"]);
      $shasse->setNomPokemon($nomPokemon);
      $shasse->setUser($user);

      // prepare() and execute()
      $entityManager->persist($shasse);
      $entityManager->flush();

      return $this->redirectToRoute('my_shasses');
    }


    $balls = $apiHttpClient->getAllBalls();
    //////////////////////////  formulaire de shasse terminée //////////////////////////
    $formCapture = $this->createForm(CaptureType::class, $shasse, [
      /* balls en paramètres pour le formType */
      // 'balls' => $balls,
    ]);
    $formCapture->handleRequest($request);

    // validation du formulaire de shiny trouvé
    if ($formCapture->isSubmitted() && $formCapture->isValid()) {

      $idCapture = filter_input(INPUT_POST, "IdCapture", FILTER_VALIDATE_INT);
      $ball = filter_input(INPUT_POST, "ball", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // on va récupérer la capture avec cet ID.
      $capture = $entityManager->getRepository(Capture::class)->find($idCapture);

      // et on lui donne les résultats du formulaire de modal :
      $capture->setSurnom($shasse->getSurnom());
      $capture->setDateCapture($shasse->getDateCapture());
      $capture->setSexe($shasse->getSexe());

      $capture->setBall($shasse->getBall());

      $capture->setSuivi(false);
      $capture->setTermine(true);

      $entityManager->flush();
    }

    $pokemons = $apiHttpClient->getAllPokemons();

    $captures = $captureRepository->findBy(['user' => $user, 'termine' => 0]);

    return $this->render('capture/shasses.html.twig', [
      "page_title" => 'Mes shasses',
      "captures" => $captures,
      "formNewShasse" => $formNewShasse,
      "formCapture" => $formCapture,
      "allPokemons" => $pokemons,
      "balls" => $balls,
    ]);
  }


  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////


  // supprimer une shasses
  #[Route('/shasse/{id}/delete', name: 'delete_shasse')]
  public function deleteShasse(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse) {
      $entityManager->remove($shasse);
      $entityManager->flush();
    }

    return $this->redirectToRoute("my_shasses");
  }

  // supprimer une capture
  #[Route('/capture/{id}/delete', name: 'delete_capture')]
  public function deleteCapture(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse) {
      $entityManager->remove($shasse);
      $entityManager->flush();
    }

    return $this->redirectToRoute("my_captures");
  }

  // retirer du suivi des shasses
  #[Route('/shasse/{id}/stop_suivi', name: 'stop_suivi')]
  public function stopSuivi(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse) {
      $shasse->setSuivi(false);
      $entityManager->flush();
    }

    return $this->redirectToRoute("my_shasses");
  }

  // ajouter au suivi des shasses
  #[Route('/shasse/{id}/add_suivi', name: 'add_suivi')]
  public function addSuivi(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse) {
      $shasse->setSuivi(true);
      $entityManager->flush();
    }

    return $this->redirectToRoute("my_shasses");
  }

  #[Route('/shasse/{id}/increment', name: 'increment_shasse')]
  public function incrementCounter(int $id, EntityManagerInterface $entityManager, Request $request): Response
  {
    $shasse = $entityManager->getRepository(Capture::class)->find($id);

    if ($shasse) {

      $shasse->setNbRencontres($shasse->getNbRencontres() + 1);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }

  #[Route('/shasse/{id}/decrement', name: 'decrement_shasse')]
  public function decrementCounter(int $id, EntityManagerInterface $entityManager, Request $request): Response
  {
    $shasse = $entityManager->getRepository(Capture::class)->find($id);

    if ($shasse) {

      $shasse->setNbRencontres($shasse->getNbRencontres() - 1);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }

  #[Route('/shasse/{id}/increment10', name: 'increment10_shasse')]
  public function increment10Counter(int $id, EntityManagerInterface $entityManager, Request $request): Response
  {
    $shasse = $entityManager->getRepository(Capture::class)->find($id);

    if ($shasse) {

      $shasse->setNbRencontres($shasse->getNbRencontres() + 10);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }

  #[Route('/shasse/{id}/decrement10', name: 'decrement10_shasse')]
  public function decrement10Counter(int $id, EntityManagerInterface $entityManager, Request $request): Response
  {
    $shasse = $entityManager->getRepository(Capture::class)->find($id);

    if ($shasse) {

      $shasse->setNbRencontres($shasse->getNbRencontres() - 10);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }

  #[Route('/shasse/{id}/updateCounter', name: 'shasse_updateCounter')]
  public function updateCounter(int $id, EntityManagerInterface $entityManager, Request $request): Response
  {
    $shasse = $entityManager->getRepository(Capture::class)->find($id);
    $newValue = (int) $request->request->get('nbRencontres');

    if ($shasse) {

      $shasse->setNbRencontres($newValue);
      $entityManager->flush();
    }
    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }
}
