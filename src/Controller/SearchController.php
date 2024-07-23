<?php

namespace App\Controller;

use App\HttpClient\ApiHttpClient;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function searchHeader(Request $request, UserRepository $userRepository, ApiHttpClient $apiHttpClient): Response
    {
        // $query = $request->query->get('q');
        $query = "pat";

        $users = $userRepository->searchByPseudo($query);
        $pokemons = $apiHttpClient->searchPokemonByQuery($query);
        dd($users);

        return $this->json([
            'users' => $users,
            'pokemons' => $pokemons,
        ]);
    }
}
