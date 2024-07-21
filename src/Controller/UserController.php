<?php

namespace App\Controller;

use App\Entity\User;
use App\HttpClient\ApiHttpClient;
use App\Repository\CaptureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(UserRepository $userRepository,): Response
    {
        $users = $userRepository->findBy([], ["pseudo" => "ASC"]);

        return $this->render('user/index.html.twig', [
            "users" => $users,
        ]);
    }

    #[Route('/users/{id}', name: 'profile_user')]
    public function profile(User $user = null, CaptureRepository $captureRepository, ApiHttpClient $apiHttpClient): Response
    {
        if (!$user) {
            dd('test');
        }

        $userCaptures = $captureRepository->findBy(["user" => $user], ["dateCapture" => "DESC"]);

        $capturesTermine = [];
        $capturesNotTermine = [];
        $totalRencontres = 0;


        foreach ($userCaptures as $capture) {
            $capture->getTermine() ? $capturesTermine[] = $capture : $capturesNotTermine[] = $capture;
            $totalRencontres += $capture->getNbRencontres();
        }

        // on récupère le nombre de pokemons uniques capturés par rapport au nombre total
        $pokemons = $apiHttpClient->getPokedex();
        $capturedPokemonIds = [];

        foreach ($capturesTermine as $pokemon) {
            $pokemonId = $pokemon->getPokedexId();
            $capturedPokemonIds[$pokemonId] = true; // On veut juste la clé (pas de doublons), on se fiche de la valeur.
        }

        return $this->render('user/profile.html.twig', [
            "page_title" => "Profil de " . $user->getPseudo(),
            "user" => $user,
            "capturesTermine" => $capturesTermine,
            "capturesNotTermine" => $capturesNotTermine,
            "totalRencontres" => $totalRencontres,
            "allPokemons" => $pokemons,
            "capturedPokemonIds" => $capturedPokemonIds,
        ]);
    }

    #[Route('/profile', name: 'my_profile')]
    public function myProfile(CaptureRepository $captureRepository, ApiHttpClient $apiHttpClient): Response
    {
        $user = $this->getUser();
        if (!$user) {
            dd('test');
        }

        $userCaptures = $captureRepository->findBy(["user" => $user], ["dateCapture" => "DESC"]);

        $capturesTermine = [];
        $capturesNotTermine = [];
        $totalRencontres = 0;

        foreach ($userCaptures as $capture) {
            $capture->getTermine() ? $capturesTermine[] = $capture : $capturesNotTermine[] = $capture;
            $totalRencontres += $capture->getNbRencontres();
        }

        // on récupère le nombre de pokemons uniques capturés par rapport au nombre total
        $pokemons = $apiHttpClient->getPokedex();
        $capturedPokemonIds = [];

        foreach ($capturesTermine as $pokemon) {
            $pokemonId = $pokemon->getPokedexId();
            $capturedPokemonIds[$pokemonId] = true; // On veut juste la clé (pas de doublons), on se fiche de la valeur.
        }

        return $this->render('user/profile.html.twig', [
            "page_title" => "Mon profil",
            "myProfil" => true,
            "user" => $user,
            "capturesTermine" => $capturesTermine,
            "capturesNotTermine" => $capturesNotTermine,
            "totalRencontres" => $totalRencontres,
            "allPokemons" => $pokemons,
            "capturedPokemonIds" => $capturedPokemonIds,
        ]);
    }
}
