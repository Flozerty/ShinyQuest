<?php

namespace App\Controller;

use App\Repository\CaptureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index_redirect')]
    #[Route('/home', name: 'app_home')]
    public function index(CaptureRepository $captureRepository, ): Response
    {
        $user = $this->getUser();

        if ($user) {
            $shasses = $captureRepository->findBy(['user' => $user, 'suivi' => 1]);

            $captures = $captureRepository->findBy(['termine' => 1], ["dateCapture" => "DESC"], 10);

            return $this->render('home/index.html.twig', [
                "page_title" => "Bienvenue sur SHINYQUEST",
                "shasses" => $shasses,
                "captures" => $captures,
            ]);
        } else {
            $captures = $captureRepository->findBy(['termine' => 1], ["dateCapture" => "DESC"], 10);

            return $this->render('home/homeDisconnected.html.twig', [
                "page_title" => "Bienvenue sur SHINYQUEST",
                "captures" => $captures,
            ]);
        }
    }
}