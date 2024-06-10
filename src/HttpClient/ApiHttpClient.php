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


  public function getPokedex(): array
  {
    $response = $this->httpClient->request('GET', "https://tyradex.vercel.app/api/v1/pokemon");
    $pokemons = $response->toArray();

    array_shift($pokemons);
    // dd($pokemons);

    $pokemonsNameSprite = [];

    foreach ($pokemons as $pokemon) {

      $pokemonsNameSprite[] =
        [
          'pokedex_id' => $pokemon["pokedex_id"],
          'generation' => $pokemon["generation"],
          'name' => $pokemon['name']['fr'],
          'spriteN' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/' . $pokemon["pokedex_id"] . '.png',
          'spriteS' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/shiny/' . $pokemon["pokedex_id"] . '.png',
          'types' => $pokemon["types"],
        ];
    }
    return $pokemonsNameSprite;
  }

  public function getPokemonInfos($id)
  {
    $response = $this->httpClient->request('GET', "pokemon/$id");
    $pokemon = $response->toArray();

    return $pokemon;
  }
}