<?php

namespace App\Controller;

use App\Entity\Capture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CaptureController extends AbstractController
{
    #[Route('/capture', name: 'app_capture')]
    public function index(): Response
    {
        return $this->render('capture/index.html.twig', [
        ]);
    }

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

    #[Route('/shasse/{id}/delete', name: 'delete_shasse')]
    public function deleteShasse(Capture $shasse = null, EntityManagerInterface $entityManager): Response
    {
        if ($shasse) {
            $entityManager->remove($shasse);
            $entityManager->flush();
        }

        return $this->redirectToRoute("my_shasses");
    }
}
