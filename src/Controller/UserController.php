<?php

namespace App\Controller;

use App\Entity\User;
use App\HttpClient\ApiHttpClient;
use App\Repository\AmisRepository;
use App\Repository\CaptureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, AmisRepository $amisRepository, CaptureRepository $captureRepository, Request $request, ApiHttpClient $apiHttpClient): Response
    {
        $users = $userRepository->findBy([], ["pseudo" => "ASC"]);
        $usersData = [];

        foreach ($users as $user) {
            $usersData[] = [
                'user' => $user,
                "capturedPokemonIds" => $this->getUserPokedexData($user, $captureRepository),
            ];
        }
        // dd($usersData);
        $query = $usersData;

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        $AmisByDemande = $amisRepository->findBy(["userDemande" => $this->getUser()]);
        $AmisByRecoit = $amisRepository->findBy(["userRecoit" => $this->getUser()], );

        $amis = [];
        $demandeEnvoyee = [];
        $demandeRecue = [];

        foreach ($AmisByDemande as $demande) {
            if ($demande->getStatut() == true) {
                $amis[] = [
                    "id" => $demande->getId(),
                    'user' => $demande->getUserRecoit(),
                ];
            } else {
                $demandeEnvoyee[] = [
                    "id" => $demande->getId(),
                    "user" => $demande->getUserRecoit(),
                ];
            }
        }

        foreach ($AmisByRecoit as $demande) {
            if ($demande->getStatut() == true) {
                $amis[] = [
                    "id" => $demande->getId(),
                    'user' => $demande->getUserDemande(),
                ];
            } else {
                $demandeRecue[] = [
                    "id" => $demande->getId(),
                    "user" => $demande->getUserDemande(),
                ];
            }
        }

        $pokemons = $apiHttpClient->getPokedex();

        return $this->render('user/index.html.twig', [
            "page_title" => "Liste des dresseurs",
            "allPokemons" => $pokemons,

            'pagination' => $pagination,
            "usersData" => $usersData,

            "amis" => $amis,
            "demandeEnvoyee" => $demandeEnvoyee,
            "demandeRecue" => $demandeRecue,
        ]);
    }

    #[Route('/profile/{pseudo}', name: 'profile_user')]
    public function profile(User $user = null, CaptureRepository $captureRepository, AmisRepository $amisRepository, ApiHttpClient $apiHttpClient): Response
    {
        if (!$user) {
            $this->addFlash('danger', 'utilisateur inexistant');
        }

        $friend = $amisRepository->findFriendship($user, $this->getUser());

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
            "page_title" => "Profil public de " . $user->getPseudo(),
            "user" => $user,
            "capturesTermine" => $capturesTermine,
            "capturesNotTermine" => $capturesNotTermine,
            "totalRencontres" => $totalRencontres,
            "allPokemons" => $pokemons,
            "capturedPokemonIds" => $capturedPokemonIds,
            'isFriend' => $friend,
        ]);
    }

    #[Route('/user/profile', name: 'my_profile')]
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

    #[Route('/user/editAvatar', name: 'change_avatar')]
    public function changeAvatar(): Response
    {
        return $this->redirectToRoute("my_profile");
    }

    #[Route('/user/editPseudo', name: 'change_pseudo')]
    public function changePseudo(): Response
    {
        return $this->redirectToRoute("my_profile");
    }


    ///////////////////////////////////////////////////////////////////////////////
    /////////////////////////////// PRIVATE METHODS ///////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    private function getUserPokedexData($user, CaptureRepository $captureRepository)
    {
        $pokemonsCaptured = $captureRepository->findBy(['user' => $user->getId(), 'termine' => true]);
        $capturedPokemonIds = [];

        foreach ($pokemonsCaptured as $pokemon) {
            $pokemonId = $pokemon->getPokedexId();
            $capturedPokemonIds[$pokemonId] = true;
        }

        return $capturedPokemonIds;
    }
}
