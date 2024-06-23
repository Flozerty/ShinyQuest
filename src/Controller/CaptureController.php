<?php

namespace App\Controller;

use App\Entity\Capture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CaptureController extends AbstractController
{
    #[Route('/capture', name: 'app_capture')]
    public function index(): Response
    {
        return $this->render('capture/index.html.twig', []);
    }

    // retirer du suivi des shasses
    #[Route('/shasse/{id}/stop_suivi', name: 'stop_suivi')]
    public function stopSuivi(Capture $shasse = null, EntityManagerInterface $entityManager): Response
    {
        if ($shasse) {
            $shasse->setSuivi(false);
            $entityManager->persist($shasse);
            $entityManager->flush();
        }

        return $this->redirectToRoute("my_shasses");
    }

    // ajouter au suivi des shasses
    #[Route('/shasse/{id}/add_suivi', name: 'add_suivi')]
    public function addSuivi(Capture $shasse = null, EntityManagerInterface $entityManager): Response
    {
        if ($shasse) {
            $shasse->setSuivi(true);
            $entityManager->persist($shasse);
            $entityManager->flush();
        }

        return $this->redirectToRoute("my_shasses");
    }

    // supprimer la shasse
    #[Route('/shasse/{id}/delete', name: 'delete_shasse')]
    public function deleteShasse(Capture $shasse = null, EntityManagerInterface $entityManager): Response
    {
        if ($shasse) {
            $entityManager->remove($shasse);
            $entityManager->flush();
        }

        return $this->redirectToRoute("my_shasses");
    }

    #[Route('/shasse/{id}/increment', name: 'increment_shasse')]
    public function incrementNbRencontres(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $shasse = $entityManager->getRepository(Capture::class)->find($id);

        if ($shasse) {

            $shasse->setNbRencontres($shasse->getNbRencontres() + 1);
            $entityManager->flush();
        }
        return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
    }

    #[Route('/shasse/{id}/decrement', name: 'decrement_shasse')]
    public function decrementCounter(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $shasse = $entityManager->getRepository(Capture::class)->find($id);

        if ($shasse) {

            $shasse->setNbRencontres($shasse->getNbRencontres() - 1);
            $entityManager->flush();
        }
        return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
    }
}
