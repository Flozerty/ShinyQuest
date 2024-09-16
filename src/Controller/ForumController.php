<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Sujet;
use App\Form\NewPostType;
use App\Form\NewSujetType;
use App\Repository\SujetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(Request $request, SujetRepository $sujetRepository): Response
    {
        $sujets = $sujetRepository->findBy([], ["date_update" => "DESC"]);

        $sujet = new Sujet;

        $formNewSujet = $this->createForm(NewSujetType::class, $sujet);
        $formNewSujet->handleRequest($request);

        if ($formNewSujet->isSubmitted() && $formNewSujet->isValid()) {

        }


        return $this->render('forum/index.html.twig', [
            "page_title" => "Bienvenue sur le forum",
            "sujets" => $sujets,
        ]);
    }

    #[Route('/forum/{titre}', name: 'sujet')]
    public function sujetContent(Request $request, SujetRepository $sujetRepository, Sujet $sujet): Response
    {
        $post = new Post;

        $formNewPost = $this->createForm(NewPostType::class, $post);
        $formNewPost->handleRequest($request);

        if ($formNewPost->isSubmitted() && $formNewPost->isValid()) {

        }

        return $this->render('forum/sujet.html.twig', [
            "page_title" => "Bienvenue sur le forum",
            "sujet" => $sujet,
        ]);
    }
}
