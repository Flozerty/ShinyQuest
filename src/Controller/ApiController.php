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
use Symfony\Component\HttpFoundation\JsonResponse;
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
      "pika" => true,
    ]);
  }

  #[Route('/api/pokemons', name: 'all_pokemons')]
  public function getPokemons(ApiHttpClient $apiHttpClient): Response
  {
    $pokemons = $apiHttpClient->getAllPokemons();

    return $this->json($pokemons);
  }

  #[Route('/api/games', name: 'all_games')]
  public function getGames(ApiHttpClient $apiHttpClient): Response
  {
    $games = $apiHttpClient->getAllGamesVersions();
    return $this->json($games);
  }

  #[Route('/{pseudo}/shinydex', name: 'app_shinydex')]
  public function shinydex(ApiHttpClient $apiHttpClient, User $user, CaptureRepository $captureRepository, UserRepository $userRepository): Response
  {
    // Get tous les pokemons
    $pokemons = $apiHttpClient->getPokedex();

    // tous les pokemons capturés par l'user
    $pokemonsCaptured = $captureRepository->findBy(['user' => $user->getId(), 'termine' => true]);
    $capturedPokemonIds = [];

    foreach ($pokemonsCaptured as $pokemon) {
      $pokemonId = $pokemon->getPokedexId();

      // on récupère les ids des pokemons capturés et leur nombre de captures
      isset($capturedPokemonIds[$pokemonId]) ? $capturedPokemonIds[$pokemonId] = $capturedPokemonIds[$pokemonId] + 1 : $capturedPokemonIds[$pokemonId] = 1;
    }
    // dd($capturedPokemonIds);

    return $this->render('api/pokedex.html.twig', [
      "page_title" => 'Shinydex de ' . $user->getPseudo(),
      'allPokemons' => $pokemons,
      // on récupère les clés du tableau d'IDs.
      'capturedPokemonIds' => $capturedPokemonIds,
      "dresseur" => $user,
    ]);
  }

  #[Route('/pokemon/{id}', name: 'pokemon_details')]
  public function pokemonDetails(ApiHttpClient $apiHttpClient, int $id, CaptureRepository $captureRepository): Response
  {
    $pokemon = $apiHttpClient->getPokemonInfos($id);
    // dd($pokemon);

    // récupération des différentes formes du pokémon
    $pokemonVarieties = [];
    $varieties = $pokemon["pkmnSpec"]["varieties"];

    if (count($varieties) > 0) {
      foreach ($varieties as $variety) {
        $url = $variety["pokemon"]["url"];
        $pokemonVarieties[] = [
          "pkmnStats" => $apiHttpClient->getRequestByUrl($url),
          "pkmnSpec" => $pokemon["pkmnSpec"]
        ];
      }
    }

    // récupération du nom du pokemon
    $name = "";
    foreach ($pokemon["pkmnSpec"]["names"] as $lang) {
      $lang["language"]["name"] == "fr" ? $name = $lang["name"] : null;
    }

    // récupération de la chaîne d'évolution du pokémon
    $url = $pokemon["pkmnSpec"]["evolution_chain"]["url"];
    $evolutionChain = $apiHttpClient->getEvolutionChain($url);

    // récupération des statss du pokemon
    $stats = [];
    foreach ($pokemon["pkmnStats"]["stats"] as $stat) {
      $url = $stat["stat"]["url"];

      $stats[] = [
        "base_stat" => $stat["base_stat"],
        "details_stat" => $apiHttpClient->getRequestByUrl($url),
      ];
    };

    // récupération des captures du pokemon
    $captures = $captureRepository->findCapturesByPokemonId($id);
    $capturesByLieu = $captureRepository->getNbCapturesByGame($id);

    $bestSpots = [];
    if (isset($capturesByLieu[0])) {
      $bestSpots = $captureRepository->getPokemonCapturesByPlacesInGame($id, $capturesByLieu[0]["jeu"]);
    }

    return $this->render('api/pokemonDetails.html.twig', [
      "name" => $name,
      'pokemon' => $pokemon,
      'stats' => $stats,
      'pokemonVarieties' => $pokemonVarieties,
      "evolutionChain" => $evolutionChain,
      "currentPokemonId" => $pokemon["pkmnStats"]["id"],
      "capturesByLieu" => $capturesByLieu,
      "captures" => $captures,
      "bestSpots" => $bestSpots,
    ]);
  }

  #[Route('/api/balls', name: 'api_balls', methods: ['GET'])]
  public function getBalls(ApiHttpClient $apiHttpClient): JsonResponse
  {
    $balls = $apiHttpClient->getAllBalls();
    return new JsonResponse($balls);
  }
}
