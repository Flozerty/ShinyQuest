<?php

namespace App\Controller;

use App\Entity\Amis;
use App\Entity\User;
use App\HttpClient\ApiHttpClient;
use App\Repository\AmisRepository;
use App\Repository\CaptureRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AmisController extends AbstractController
{

    // Afficher toutes mes demandes d'amis
    #[Route('/amis', name: 'app_amis')]
    public function index(ApiHttpClient $apiHttpClient, AmisRepository $amisRepository, CaptureRepository $captureRepository, UserRepository $userRepository, ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute("app_home");
        }

        $allPokemons = $apiHttpClient->getPokedex();

        $AmisByDemande = $amisRepository->findBy(["userDemande" => $this->getUser()]);
        $AmisByRecoit = $amisRepository->findBy(["userRecoit" => $this->getUser()], );

        $dresseursData = [];
        $demandeEnvoyee = [];
        $demandeRecue = [];

        foreach ($AmisByDemande as $demande) {
            if ($demande->getStatut() == true) {
                $dresseursData[] = [
                    "id" => $demande->getId(),
                    'user' => $demande->getUserRecoit(),
                    "capturedPokemonIds" => $this->getUserPokedexData($demande->getUserRecoit(), $captureRepository),
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
                $dresseursData[] = [
                    "id" => $demande->getId(),
                    'user' => $demande->getUserDemande(),
                    "capturedPokemonIds" => $this->getUserPokedexData($demande->getUserDemande(), $captureRepository),
                ];
            } else {
                $demandeRecue[] = [
                    "id" => $demande->getId(),
                    "user" => $demande->getUserDemande(),
                ];
            }
        }

        // trier par user->getPseudo()
        usort($dresseursData, function ($a, $b) {
            return strcmp($a['user']->getPseudo(), $b['user']->getPseudo());
        });

        // dd($AmisByRecoit);

        return $this->render('amis/index.html.twig', [
            'dresseursAmis' => $dresseursData,
            'demandesEnvoyees' => $demandeEnvoyee,
            'demandesRecues' => $demandeRecue,
            "allPokemons" => $allPokemons,
        ]);
    }

    // Supprimer / Refuser une demande d'ami
    #[Route('/amis/{id}/delete', name: 'delete_ami')]
    public function deleteAmi(AmisRepository $amisRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $lienAmis = $amisRepository->find($id);

        if ($lienAmis && (in_array('ROLE_ADMIN', $this->getUser()->getRoles()) || $lienAmis->getUserDemande() == $this->getUser() || $lienAmis->getUserRecoit() == $this->getUser())) {
            $entityManager->remove($lienAmis);
            $entityManager->flush();
        }

        $this->addFlash(
            'notice',
            "La demande d'ami a été annulée."
        );

        return $this->redirectToRoute("app_amis");
    }

    #[Route('/users/{id}/delete', name: 'delete_ami_users')]
    public function deleteAmiUsers(AmisRepository $amisRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $lienAmis = $amisRepository->find($id);

        if ($lienAmis && (in_array('ROLE_ADMIN', $this->getUser()->getRoles()) || $lienAmis->getUserDemande() == $this->getUser() || $lienAmis->getUserRecoit() == $this->getUser())) {
            $entityManager->remove($lienAmis);
            $entityManager->flush();
        }

        $this->addFlash(
            'notice',
            "La demande d'ami a été annulée."
        );

        return $this->redirectToRoute("app_users");
    }

    // Accepter une demande d'ami
    #[Route('/amis/{id}/accept', name: 'accept_ami')]
    public function acceptAmi(AmisRepository $amisRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $lienAmis = $amisRepository->find($id);

        if ($lienAmis) {
            $lienAmis->setStatut(true);
            $entityManager->flush();
        }

        $this->addFlash(
            'notice',
            "La demande d'ami a été acceptée!"
        );

        return $this->redirectToRoute("app_amis");
    }

    #[Route('/users/{id}/accept', name: 'accept_ami_users')]
    public function acceptAmiUsers(AmisRepository $amisRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $lienAmis = $amisRepository->find($id);

        if ($lienAmis) {
            $lienAmis->setStatut(true);
            $entityManager->flush();
        }

        $this->addFlash(
            'notice',
            "La demande d'ami a été acceptée!"
        );

        return $this->redirectToRoute("app_users");
    }

    // Envoyerune demande d'ami
    #[Route('/amis/{pseudo}/demand', name: 'demande_ami')]
    public function demandeAmi(AmisRepository $amisRepository, EntityManagerInterface $entityManager, User $userEnvoi = null): Response
    {
        $user = $this->getUser();

        if ($user == $userEnvoi) {
            dd("envoi a soi-meme");
        }

        if (!$user) {
            // si il n'y a pas d'user connecté
            dd('pas co');
        }

        if (!$userEnvoi) {
            // si l'user demandé n'existe pas
            dd('pas trouvé');
        }

        if (
            $amisRepository->findBy(["userDemande" => $user, "userRecoit" => $userEnvoi]) ||
            $amisRepository->findBy(["userRecoit" => $user, "userDemande" => $userEnvoi])
        ) {
            // S'il existe déjà une demande d'ami entre les deux users
            dd('déja demandé');
        }

        // Si les conditions passent, on créait la demande d'ami
        $demande = new Amis;
        $demande->setUserDemande($user);
        $demande->setUserRecoit($userEnvoi);
        $demande->setStatut(false);
        $demande->setDateDemande(new DateTime());

        $entityManager->persist($demande);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            "Demande d'ami envoyée!"
        );

        return $this->redirectToRoute("app_users");
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
