<?php

namespace App\Controller;

use App\HttpClient\ApiHttpClient;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function searchHeader(Request $request, UserRepository $userRepository, ApiHttpClient $apiHttpClient): Response
    {
        $query = $request->query->get('q');
        // $query = "pat";

        $pokemons = $apiHttpClient->searchPokemonByQuery($query);
        $users = $userRepository->searchByPseudo($query); // tableau d'objets

        // on récupère l'id et le pseudo de l'user.
        $usersContent = [];
        foreach($users as $user) {
            $usersContent[] = [
                "id" => $user->getId(),
                "pseudo" => $user->getPseudo()
            ];
        }

        return new JsonResponse([
            'users' => $usersContent,
            'pokemons' => $pokemons,
        ]);
    }
}
