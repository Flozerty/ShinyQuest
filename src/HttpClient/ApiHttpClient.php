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
  public function getPokemons(): array
  {
    // pagination pour éviter de charger trop de données d'un coup
    $pokemonsNameSprite = [];

    for ($i = 1; $i <= 1025; $i++) {
      $response = $this->httpClient->request('GET', "pokemon/$i");
      $pokemon = $response->toArray();
      $pokemonsNameSprite[] = [
        'name' => $pokemon['name'],
        'spriteN' => $pokemon['sprites']['other']['official-artwork']['front_default'],
        'spriteS' => $pokemon['sprites']['other']['official-artwork']['front_shiny'],
      ];
    }

    return $pokemonsNameSprite;
  }
}