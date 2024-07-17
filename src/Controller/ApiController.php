<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\User;
use App\Form\CaptureType;
use App\Form\ShasseStartType;
use App\HttpClient\ApiHttpClient;
use App\Repository\CaptureRepository;
use App\Repository\UserRepository;
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

    return $this->render('api/pokedex.html.twig', [
      "page_title" => 'Recherche par Pokédex',
      'allPokemons' => $pokemons,
    ]);
  }

  #[Route('/shinydex/{id}', name: 'app_shinydex')]
  public function shinydex(ApiHttpClient $apiHttpClient, int $id, CaptureRepository $captureRepository, UserRepository $userRepository): Response
  {
    $dresseur = $userRepository->findOneBy(['id' => $id]);

    // Get tous les pokemons
    $pokemons = $apiHttpClient->getPokedex();

    // tous les pokemons capturés par l'user
    $pokemonsCaptured = $captureRepository->findBy(['user' => $id, 'termine' => true]);
    $capturedPokemonIds = [];

    foreach ($pokemonsCaptured as $pokemon) {
      $pokemonId = $pokemon->getPokedexId();

      $capturedPokemonIds[$pokemonId] = true; // On veut juste la clé (pas de doublons), on se fiche de la valeur.
    }

    return $this->render('api/pokedex.html.twig', [
      "page_title" => 'Shinydex de ' . $dresseur->getPseudo(),
      'allPokemons' => $pokemons,
      // on récupère les clés du tableau d'IDs.
      'capturedPokemonIds' => array_keys($capturedPokemonIds),
      "dresseur" => $dresseur,
    ]);
  }

  // #[Route('/test', name: 'test')]
  // public function test(ApiHttpClient $apiHttpClient): Response
  // {

  //   $games = $apiHttpClient->getAllGamesVersions();

  //   dd($games);

  //   return $this->render('api/index.html.twig', [
  //     // on récupère les clés du tableau d'IDs.
  //   ]);
  // }
}
