<?php

namespace App\Controller;

use App\HttpClient\ApiHttpClient;
use App\Repository\AmisRepository;
use App\Repository\CaptureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AmisController extends AbstractController
{
    #[Route('/amis', name: 'app_amis')]
    public function index(ApiHttpClient $apiHttpClient, AmisRepository $amisRepository, CaptureRepository $captureRepository, UserRepository $userRepository,): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $allPokemons = $apiHttpClient->getPokedex();

        $dresseursData = [];



        $AmisByDemande = $amisRepository->findBy(["userDemande" => $this->getUser()], );

        $AmisByRecoit = $amisRepository->findBy(["userRecoit" => $this->getUser()], );
        
        dd($AmisByRecoit);

        $users =  $userRepository->findBy([], ["pseudo" => "ASC"]);


        // /////////////////////////////////////////////////////////////:
        
        foreach($users as $user) {
            // même méthode que dans ApiController   =>   #[Route('/shinydex/{id}', name: 'app_shinydex')]
            $pokemonsCaptured = $captureRepository->findBy(['user' => $user->getId(), 'termine' => true]);
            $capturedPokemonIds = [];

            foreach ($pokemonsCaptured as $pokemon) {
                $pokemonId = $pokemon->getPokedexId();
                $capturedPokemonIds[$pokemonId] = true;
            }

            // ajout de la data du user.
            $dresseursData[] = [
                'user' => $user,
                'capturedPokemonIds' => $capturedPokemonIds,
            ];
        }

        return $this->render('amis/index.html.twig', [
            'dresseurs' => $dresseursData,
            "allPokemons" => $allPokemons,
        ]);
    }
}
