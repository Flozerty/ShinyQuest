<?php

namespace App\Controller;

use App\HttpClient\ApiHttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{

    #[Route('/pokedex', name: 'pokedex_classic')]
    public function index(ApiHttpClient $apiHttpClient): Response
    {
        $pokemons = $apiHttpClient->getPokemons();
        // d($users);
        return $this->render('api/index.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }
}
