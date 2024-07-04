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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiController extends AbstractController
{

  #[Route('/pokedex', name: 'app_pokedex')]
  public function pokedex(ApiHttpClient $apiHttpClient): Response
  {
    $pokemons = $apiHttpClient->getPokedex();

    // dd($pokemons);
    return $this->render('api/pokedex.html.twig', [
      "page_title" => 'Recherche par Pokédex',
      'allPokemons' => $pokemons,
    ]);
  }

  // Toutes mes captures
  #[Route('/captures', name: 'my_captures')]
  public function captures(ApiHttpClient $apiHttpClient, CaptureRepository $captureRepository): Response
  {

    $user = $this->getUser();
    if (!$user) {
      return $this->redirectToRoute('app_login');
    }

    $captures = $captureRepository->findBy(['user' => $user, 'termine' => 1]);

    return $this->render('api/captures.html.twig', [
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

    $shasse = new Capture;

    //  formulaire de création de nouvelle shasse
    $formNewShasse = $this->createForm(ShasseStartType::class, $shasse);

    $formNewShasse->handleRequest($request);

    // validation du formulaire de nouvelle shasse
    if ($formNewShasse->isSubmitted() && $formNewShasse->isValid()) {

      $pokemonId = filter_input(INPUT_POST, "pokemonId", FILTER_VALIDATE_INT);

      // on récupère le nom du pokemon, son sprite, etc.
      $pokemon = $apiHttpClient->getPokemonInfos($pokemonId);
      $nomPokemon = $apiHttpClient->getPokemonNameById($pokemonId);


      $shasse = $formNewShasse->getData();

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


    //  formulaire de shasse terminée
    $formCapture = $this->createForm(CaptureType::class, $shasse);

    $formCapture->handleRequest($request);

    // validation du formulaire de shiny trouvé
    if ($formCapture->isSubmitted() && $formCapture->isValid()) {

      $idCapture = filter_input(INPUT_POST, "IdCapture", FILTER_VALIDATE_INT);
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

    return $this->render('api/shasses.html.twig', [
      "page_title" => 'Mes shasses',
      "captures" => $captures,
      "formNewShasse" => $formNewShasse,
      "formCapture" => $formCapture,
      "allPokemons" => $pokemons,
    ]);
  }
}
