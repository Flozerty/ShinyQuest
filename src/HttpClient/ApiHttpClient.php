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
  public function getPokemons()
  {
    $response = $this->httpClient->request('GET', "pokemon/?offest=1302&limit=1302", [
      'verify_peer' => false,
    ]);
    return $response->toArray();
  }
}