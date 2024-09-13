<?php

namespace App\Controller;

use App\Entity\Sujet;
use App\Repository\SujetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(SujetRepository $sujetRepository): Response
    {
        $sujets = $sujetRepository->findBy([], ["date_update" => "DESC"]);

        return $this->render('forum/index.html.twig', [
            "page_title" => "Bienvenue sur le forum",
            "sujets" => $sujets,
        ]);
    }

    #[Route('/forum/{titre}', name: 'sujet')]
    public function sujetContent(SujetRepository $sujetRepository, Sujet $sujet): Response
    {

        return $this->render('forum/sujet.html.twig', [
            "page_title" => "Bienvenue sur le forum",
            "sujet" => $sujet,
        ]);
    }
}
