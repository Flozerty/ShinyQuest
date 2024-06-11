<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\User;
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
      "page_title" => 'Pokédex',
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

    // nouvelle shasse
    $shasse = new Capture;
    $formNewShasse = $this->createForm(ShasseStartType::class, $shasse);

    $formNewShasse->handleRequest($request);

    if ($formNewShasse->isSubmitted() && $formNewShasse->isValid()) {
      // validation du formulaire de nouvelle shasse

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

    $pokemons = $apiHttpClient->getAllPokemons();


    $captures = $captureRepository->findBy(['user' => $user, 'termine' => 0]);

    return $this->render('api/shasses.html.twig', [
      "page_title" => 'Mes shasses',
      "captures" => $captures,
      "formNewShasse" => $formNewShasse,
      "allPokemons" => $pokemons,
    ]);
  }
}