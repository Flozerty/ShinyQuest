<?php

namespace App\Controller;

use App\Entity\User;
use App\HttpClient\ApiHttpClient;
use App\Repository\CaptureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
  public function shasses(ApiHttpClient $apiHttpClient, CaptureRepository $captureRepository): Response
  {

    $user = $this->getUser();
    if (!$user) {
      return $this->redirectToRoute('app_login');
    }
    $captures = $captureRepository->findBy(['user' => $user, 'termine' => 0]);

    return $this->render('api/shasses.html.twig', [
      "page_title" => 'Mes shasses',
      "captures" => $captures,
    ]);
  }
}