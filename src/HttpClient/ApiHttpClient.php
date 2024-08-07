<?php

namespace App\HttpClient;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiHttpClient extends AbstractController
{
  private $httpClient;

  public function __construct(HttpClientInterface $jph)
  {
    $this->httpClient = $jph;
  }

  // Get all pkmns with name + gen + id + img + types
  public function getPokedex(): array
  {
    $response = $this->httpClient->request('GET', "https://tyradex.vercel.app/api/v1/pokemon");
    $pokemons = $response->toArray();

    // on enlève le "pokemon 0"
    array_shift($pokemons);
    $allPokemons = [];

    foreach ($pokemons as $pokemon) {

      $allPokemons[] =
        [
          'pokedex_id' => $pokemon["pokedex_id"],
          'generation' => $pokemon["generation"],
          'name' => $pokemon['name']['fr'],
          'spriteN' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/' . $pokemon["pokedex_id"] . '.png',
          'spriteS' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/shiny/' . $pokemon["pokedex_id"] . '.png',
          'types' => $pokemon["types"],
        ];
    }
    return $allPokemons;
  }

  // Récupérer l'id dans une url
  function getIdFromUrl($url)
  {
    $parts = explode('/', rtrim($url, '/'));
    return end($parts);
  }

  // Get a single pkmn "allInfos"
  public function getPokemonInfos($id)
  {
    $response = $this->httpClient->request('GET', "pokemon/$id");
    $pokemon = $response->toArray();

    $urlSpec = $pokemon["species"]["url"];
    $pokemonSpec = $this->getRequestByUrl($urlSpec);

    return ["pkmnStats" => $pokemon, "pkmnSpec" => $pokemonSpec];
  }

  public function getRequestByUrl($url)
  {
    $response = $this->httpClient->request('GET', $url);
    return $response->toArray();
  }

  // Récupérer la chaîne d'évolution d'un pokémon
  public function getEvolutionChain($url)
  {
    $evolutionChain = $this->getRequestByUrl($url);
    $pokemons = [];

    $this->extractEvolutionDetails($evolutionChain['chain'], $pokemons);
    // dd($pokemons);

    // on renvoie le tri de la chaîne d'évolution (voir 2 fonctions plus bas)
    return $this->sortEvolutionChain($pokemons);
  }

  // extraire tous les pokémons de la chaîne d'évolution
  private function extractEvolutionDetails($chain, &$pokemons)
  // On utilise ici &$pokemons pour que les modifications apportées à $pokemons soient apportées à la variable d'origine, dans la fonction getEvolutionChain(), on n'a donc pas besoin de return ici.
  {
    $pokemonName = $chain['species']['name'];
    $pokemonDetails = $this->getPokemonInfos($pokemonName);
    $pokemons[] = $pokemonDetails;

    if (!empty($chain['evolves_to'])) {
      foreach ($chain['evolves_to'] as $evolution) {
        $this->extractEvolutionDetails($evolution, $pokemons);
      }
    }
  }

  // triage de la chaîne d'évolution
  private function sortEvolutionChain($evolutionChain)
  {
    $tree = [];
    $lookup = [];

    // Initialiser le lookup avec chaque Pokémon
    foreach ($evolutionChain as $pokemon) {
      $lookup[$pokemon['pkmnSpec']['id']] = [
        'pokemon' => $pokemon,
        'children' => []
      ];
    }

    // Construire l'arbre d'évolution
    foreach ($evolutionChain as $pokemon) {
      $evolvesFromUrl = $pokemon['pkmnSpec']['evolves_from_species']['url'] ?? null;
      $evolvesFromId = $evolvesFromUrl ? $this->getIdFromUrl($evolvesFromUrl) : null;

      if ($evolvesFromId && isset($lookup[$evolvesFromId])) {
        $lookup[$evolvesFromId]['children'][] = &$lookup[$pokemon['pkmnSpec']['id']];
      } else {
        $tree[] = &$lookup[$pokemon['pkmnSpec']['id']];
      }
    }
    // dd($tree);

    return $tree;
  }

  // Get all pkmns with only name + id (pour form)
  public function getAllPokemons(): array
  {
    $response = $this->httpClient->request('GET', "https://tyradex.vercel.app/api/v1/pokemon");
    $pokemons = $response->toArray();

    // on enlève le "pokemon 0"
    array_shift($pokemons);
    $allPokemons = [];

    foreach ($pokemons as $pokemon) {
      $allPokemons[] =
        [
          'pokedex_id' => $pokemon["pokedex_id"],
          'name' => $pokemon['name']['fr'],
        ];
    }
    // dd($allPokemons);

    return $allPokemons;
  }

  public function getPokemonNameById($id)
  {
    $response = $this->httpClient->request('GET', "https://tyradex.vercel.app/api/v1/pokemon/$id");
    $pokemon = $response->toArray();
    return $pokemon["name"]["fr"];
  }


  // Récupération de toutes les versions & leurs id
  public function getAllGamesVersions()
  {
    $response = $this->httpClient->request('GET', "version/?offset=0&limit=100");
    $allGames = $response->toArray()["results"];

    // On va chercher tous les noms des jeux en français
    $frGames = [];
    foreach ($allGames as $game) {
      $url = $game["url"];

      $newResponse = $this->httpClient->request('GET', $url);
      $gameNames = $newResponse->toArray();

      // $idVersion = $gameNames["id"];

      foreach ($gameNames['names'] as $name) {

        if ($name["language"]["name"] === "fr") {

          $frGames[] = $name["name"];
        }
      }
    }

    return $frGames;
  }

  // Récupérer tous les types de balls
  public function getAllBalls()
  {
    $response = $this->httpClient->request('GET', "item-pocket/3/");
    $allBallsCategories = $response->toArray()["categories"];

    $allBallsData = [];

    foreach ($allBallsCategories as $category) {

      $url = $category["url"];

      // on effectue une nouvelle requête pour la catégorie de  ballsen cours
      $newResponse = $this->httpClient->request('GET', $url);
      $categoryData = $newResponse->toArray();

      $nameOfCategory = $categoryData["names"][0]["name"];
      $ballsOfCategory = [];

      // on récupère toutes les balls de la catégorie
      foreach ($categoryData['items'] as $ball) {

        $url = $ball["url"];

        $ballsOfCategory[] = $this->getBallData($url);
      }

      // On ajoute le contenu de la catégorie de balls à la data 
      $allBallsData[] = [
        "categoryName" => $nameOfCategory,
        "ballsData" => $ballsOfCategory,
      ];
    }

    return $allBallsData;
  }

  // Récupération des infos d'une seule ball
  public function getBallData($url)
  {
    $response = $this->httpClient->request('GET', $url);
    $ballData = $response->toArray();

    // on cherche le nom de la ball en français.
    foreach ($ballData['names'] as $name) {

      if ($name["language"]["name"] === "fr") {

        return [
          "spriteName" => $ballData["name"],
          "name" => $name["name"],
        ];
      }
    }
  }

  // Recherche d'un pokemon par chaîne de caractère / id
  public function searchPokemonByQuery(string $query): array
  {
    $data = $this->getAllPokemons();
    $filteredPokemons = [];

    foreach ($data as $pokemon) {

      // On ajoute bien !== false pour quand $query est en position [0] du strpos, 
      if (strpos(strtolower($pokemon["name"]), strtolower($query)) !== false) {
        $filteredPokemons[] = $pokemon;
      }
    }
    return $filteredPokemons;
  }
}
