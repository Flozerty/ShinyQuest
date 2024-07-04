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

    return $this->render('api/pokedex.html.twig', [
      "page_title" => 'Recherche par Pokédex',
      'allPokemons' => $pokemons,
    ]);
  }

  #[Route('/shinydex', name: 'app_shinydex')]
  public function shinydex(ApiHttpClient $apiHttpClient, CaptureRepository $captureRepository): Response
  {
    $pokemons = $apiHttpClient->getPokedex();

    $pokemonsCaptured = $captureRepository->findBy(['termine' => true]);
    $capturedPokemonIds = [];

    foreach ($pokemonsCaptured as $pokemon) {
      $pokemonId = $pokemon->getId();

      $capturedPokemonIds[$pokemonId] = true; // On veut juste la clé (pas de doublons), on se fiche de la valeur.
    }

    return $this->render('api/pokedex.html.twig', [
      "page_title" => 'Recherche par Pokédex',
      'allPokemons' => $pokemons,
      // on récupère les clés du tableau d'IDs.
      'capturedPokemonIds' => array_keys($capturedPokemonIds),
    ]);
  }
}
