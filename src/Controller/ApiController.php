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
    $generationsIds = $apiHttpClient->getAllGenerations();
    return $this->render('api/pokedex.html.twig', [
      "page_title" => 'Recherche par Pokédex',
      'allPokemons' => $pokemons,
      "generations" => $generationsIds,
      "pika" => true,
    ]);
  }

  // page du shinydex
  #[Route('/{pseudo}/shinydex', name: 'app_shinydex')]
  public function shinydex(ApiHttpClient $apiHttpClient, User $user, CaptureRepository $captureRepository, UserRepository $userRepository): Response
  {
    $pokemons = $apiHttpClient->getPokedex();
    $generationsIds = $apiHttpClient->getAllGenerations();

    // tous les pokemons capturés par l'user
    $pokemonsCaptured = $captureRepository->findBy(['user' => $user->getId(), 'termine' => true]);
    $capturedPokemonIds = [];

    foreach ($pokemonsCaptured as $pokemon) {
      $pokemonId = $pokemon->getPokedexId();

      // on récupère les ids des pokemons capturés et leur nombre de captures
      isset($capturedPokemonIds[$pokemonId]) ? $capturedPokemonIds[$pokemonId] = $capturedPokemonIds[$pokemonId] + 1 : $capturedPokemonIds[$pokemonId] = 1;
    }

    return $this->render('api/pokedex.html.twig', [
      "page_title" => 'Shinydex de ' . $user->getPseudo(),
      'allPokemons' => $pokemons,
      // on récupère les clés du tableau d'IDs.
      'capturedPokemonIds' => $capturedPokemonIds,
      "generations" => $generationsIds,
      "dresseur" => $user,
      "pika" => true,
    ]);
  }

  // pokedex des pokémons d'une génération
  #[Route('/pokedex/generation/{id}', name: 'generation_pokedex')]
  public function getGenerationPokemons(ApiHttpClient $apiHttpClient, int $id): Response
  {
    $pokemons = $apiHttpClient->getPokemonsByGeneration($id);
    $html = '';
    foreach ($pokemons as $pokemon) {
      $html .=
        '<a href="/pokemon/' . $pokemon['pokedex_id'] . '">' .
        $this->renderView('.custom/pokedexCard.html.twig', ['pokemon' => $pokemon,]) .
        '</a>';
    }

    return $this->json(['html' => $html]);
  }

  // pokedex entier en json
  #[Route('/pokedex/all', name: 'pokedex_all')]
  public function pokedexJson(ApiHttpClient $apiHttpClient): Response
  {
    $pokemons = $apiHttpClient->getPokedex();
    $html = '';
    foreach ($pokemons as $pokemon) {
      $html .=
        '<a href="/pokemon/' . $pokemon['pokedex_id'] . '">' .
        $this->renderView('.custom/pokedexCard.html.twig', ['pokemon' => $pokemon,]) .
        '</a>';
    }

    return $this->json(['html' => $html]);
  }

  // shinydex des pokémons d'une génération
  #[Route('/{pseudo}/shinydex/generation/{id}', name: 'generation_shinydex')]
  public function getGenerationShinydex(ApiHttpClient $apiHttpClient, UserRepository $userRepository, string $pseudo, int $id, CaptureRepository $captureRepository): Response
  {
    $pokemons = $apiHttpClient->getPokemonsByGeneration($id);
    $user = $userRepository->findOneBy(['pseudo' => $pseudo]);
    $pokemonsCaptured = $captureRepository->findBy(['user' => $user, 'termine' => true]);

    $capturedPokemonIds = [];
    foreach ($pokemonsCaptured as $pokemon) {
      $pokemonId = $pokemon->getPokedexId();
      // on récupère les ids des pokemons capturés et leur nombre de captures
      isset($capturedPokemonIds[$pokemonId]) ? $capturedPokemonIds[$pokemonId] = $capturedPokemonIds[$pokemonId] + 1 : $capturedPokemonIds[$pokemonId] = 1;
    }

    $html = '';
    foreach ($pokemons as $pokemon) {
      if (in_array($pokemon['pokedex_id'], array_keys($capturedPokemonIds))) {
        $html .=
          '<a href="/pokemon/' . $pokemon['pokedex_id'] . '">' .
          $this->renderView('.custom/pokedexCard.html.twig', ['pokemon' => $pokemon, 'capturedPokemonIds' => $capturedPokemonIds]) .
          '</a>';

      } else {
        $html .= $this->renderView('.custom/pokedexCard.html.twig', [
          'pokemon' => $pokemon,
          'capturedPokemonIds' => $capturedPokemonIds,
        ]);
      }
    }
    return $this->json(['html' => $html]);
  }

  // pokedex entier en json
  #[Route('/{pseudo}/shinydex/all', name: 'shinydex_all')]
  public function shinydexJson(ApiHttpClient $apiHttpClient, User $user, CaptureRepository $captureRepository): Response
  {
    $pokemons = $apiHttpClient->getPokedex();
    $pokemonsCaptured = $captureRepository->findBy(['user' => $user, 'termine' => true]);

    $capturedPokemonIds = [];
    foreach ($pokemonsCaptured as $pokemon) {
      $pokemonId = $pokemon->getPokedexId();
      // on récupère les ids des pokemons capturés et leur nombre de captures
      isset($capturedPokemonIds[$pokemonId]) ? $capturedPokemonIds[$pokemonId] = $capturedPokemonIds[$pokemonId] + 1 : $capturedPokemonIds[$pokemonId] = 1;
    }

    $html = '';
    foreach ($pokemons as $pokemon) {

      if (in_array($pokemon['pokedex_id'], array_keys($capturedPokemonIds))) {
        $html .=
          '<a href="/pokemon/' . $pokemon['pokedex_id'] . '">' .
          $this->renderView('.custom/pokedexCard.html.twig', ['pokemon' => $pokemon, 'capturedPokemonIds' => $capturedPokemonIds]) .
          '</a>';

      } else {
        $html .= $this->renderView('.custom/pokedexCard.html.twig', [
          'pokemon' => $pokemon,
          'capturedPokemonIds' => $capturedPokemonIds,
        ]);
      }
    }
    return $this->json(['html' => $html]);
  }

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////

  // récupération de tous les pokemons
  #[Route('/api/pokemons', name: 'all_pokemons')]
  public function getPokemons(ApiHttpClient $apiHttpClient): Response
  {
    $pokemons = $apiHttpClient->getAllPokemons();
    return $this->json($pokemons);
  }

  // récupération de tous les jeux
  #[Route('/api/games', name: 'all_games')]
  public function getGames(ApiHttpClient $apiHttpClient): Response
  {
    $games = $apiHttpClient->getAllGamesVersions();
    return $this->json($games);
  }

  // Page de détails d'un pokémon
  #[Route('/pokemon/{id}', name: 'pokemon_details')]
  public function pokemonDetails(ApiHttpClient $apiHttpClient, int $id, CaptureRepository $captureRepository): Response
  {
    $data = $this->getPokemonData($apiHttpClient, $id, $captureRepository);
    return $this->render('api/pokemonDetails.html.twig', $data);
  }

  // Détails d'un pokémon en ajax
  #[Route('/api/pokemonDetailsChange/{id}', name: 'api_details_change', methods: ['GET'])]
  public function getPokemonDetails(ApiHttpClient $apiHttpClient, int $id, CaptureRepository $captureRepository): JsonResponse
  {
    $data = $this->getPokemonData($apiHttpClient, $id, $captureRepository);
    return new JsonResponse($data);
  }

  // récupération de toutes les balls
  #[Route('/api/balls', name: 'api_balls', methods: ['GET'])]
  public function getBalls(ApiHttpClient $apiHttpClient): JsonResponse
  {
    $balls = $apiHttpClient->getAllBalls();
    return new JsonResponse($balls);
  }

  ///////////////////////////////////////////////////////////////////////////////
  /////////////////////////////// PRIVATE METHODS ///////////////////////////////
  ///////////////////////////////////////////////////////////////////////////////
  private function getPokemonData(ApiHttpClient $apiHttpClient, int $id, CaptureRepository $captureRepository): array
  {
    $pokemon = $apiHttpClient->getPokemonInfos($id);
    $data = $apiHttpClient->getDataByPokemonDetails($pokemon);

    $captures = $captureRepository->findCapturesByPokemonId($id);
    $capturesByLieu = $captureRepository->getNbCapturesByGame($id);
    $bestSpots = [];
    if (isset($capturesByLieu[0])) {
      $bestSpots = $captureRepository->getPokemonCapturesByPlacesInGame($id, $capturesByLieu[0]["jeu"]);
    }
    // dd([
    //   'pokemon' => $pokemon,
    //   'data' => $data,
    //   'currentPokemonId' => $pokemon["pkmnStats"]["id"],
    //   'capturesByLieu' => $capturesByLieu,
    //   'captures' => $captures,
    //   'bestSpots' => $bestSpots,
    //   'pika' => true,
    // ]);
    return [
      'pokemon' => $pokemon,
      'data' => $data,
      'currentPokemonId' => $pokemon["pkmnStats"]["id"],
      'capturesByLieu' => $capturesByLieu,
      'captures' => $captures,
      'bestSpots' => $bestSpots,
      'pika' => true,
    ];
  }
}
