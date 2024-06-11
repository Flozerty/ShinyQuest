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

  // get all pkmns with name + gen + id + img + types
  public function getPokedex(): array
  {
    $response = $this->httpClient->request('GET', "https://tyradex.vercel.app/api/v1/pokemon");
    $pokemons = $response->toArray();

    // on enlève le "pokemon 0"
    array_shift($pokemons);
    // dd($pokemons);

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

  // get a single pkmn "allInfos"
  public function getPokemonInfos($id)
  {
    $response = $this->httpClient->request('GET', "pokemon/$id");
    $pokemon = $response->toArray();
    return $pokemon;
  }

  // get all pkmns with only name + id
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
}