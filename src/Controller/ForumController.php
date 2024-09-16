<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Sujet;
use App\Form\NewPostType;
use App\Form\NewSujetType;
use App\Repository\PostRepository;
use App\Repository\SujetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ForumController extends AbstractController
{
  #[Route('/forum', name: 'app_forum')]
  public function index(Request $request, EntityManagerInterface $entityManager, SujetRepository $sujetRepository): Response
  {
    $sujets = $sujetRepository->findBy([], ["date_update" => "DESC"]);

    $sujet = new Sujet;

    $formNewSujet = $this->createForm(NewSujetType::class, $sujet);
    $formNewSujet->handleRequest($request);

    if ($formNewSujet->isSubmitted() && $formNewSujet->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('sujet', ['titre' => $sujet->getTitre()]);

    }

    return $this->render('forum/index.html.twig', [
      "page_title" => "Bienvenue sur le forum",
      "sujets" => $sujets,
      "form" => $formNewSujet,
    ]);
  }

  #[Route('/forum/{titre}', name: 'sujet')]
  public function sujetContent(Request $request, PostRepository $postRepository, EntityManagerInterface $entityManager, SujetRepository $sujetRepository, Sujet $sujet): Response
  {
    $posts = $postRepository->findBy(["sujet" => $sujet]);

    // formulaire de nouveau post
    $post = new Post;

    $formNewPost = $this->createForm(NewPostType::class, $post);
    $formNewPost->handleRequest($request);

    if ($formNewPost->isSubmitted() && $formNewPost->isValid()) {

      $entityManager->flush();

      return $this->redirectToRoute('sujet', ['titre' => $sujet->getTitre()]);
    }

    return $this->render('forum/sujet.html.twig', [
      "page_title" => $sujet->getTitre(),
      "sujet" => $sujet,
      "posts" => $posts,
      "form" => $formNewPost,
    ]);
  }
}
