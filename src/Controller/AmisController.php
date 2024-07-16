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

        $AmisByDemande = $amisRepository->findBy(["userDemande" => $this->getUser()]);
        $AmisByRecoit = $amisRepository->findBy(["userRecoit" => $this->getUser()],);

        $dresseursData = [];
        $demandeEnvoyee = [];
        $demandeRecue = [];

        foreach ($AmisByDemande as $demande) {
            if ($demande->getStatut() == true) {
                $dresseursData[] = [
                    'user' => $demande->getUserRecoit(),
                    "capturedPokemonIds" => $this->getUserPokedexData($demande->getUserRecoit(), $captureRepository),
                ];
            } else {
                $demandeEnvoyee[] = $demande->getUserRecoit();
            }
        }

        foreach ($AmisByRecoit as $demande) {
            if ($demande->getStatut() == true) {
                $dresseursData[] = [
                    'user' => $demande->getUserDemande(),
                    "capturedPokemonIds" => $this->getUserPokedexData($demande->getUserDemande(), $captureRepository),
                ];
            } else {
                $demandeRecue[] = $demande->getUserDemande();
            }
        }

        // trier par user->getPseudo()
        usort($dresseursData, function($a, $b) {
            return strcmp($a['user']->getPseudo(), $b['user']->getPseudo());
        });

        return $this->render('amis/index.html.twig', [
            'dresseursAmis' => $dresseursData,
            'demandesEnvoyees' => $demandeEnvoyee,
            'demandesRecues' => $demandeRecue,
            "allPokemons" => $allPokemons,
        ]);
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
