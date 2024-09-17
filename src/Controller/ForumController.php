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
  public function index(SujetRepository $sujetRepository): Response
  {
    $sujets = $sujetRepository->findBy([], ["date_update" => "DESC"]);

    return $this->render('forum/index.html.twig', [
      "page_title" => "Bienvenue sur le forum",
      "sujets" => $sujets,
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
      $post->setUser($this->getUser());
      $post->setSujet($sujet);

      $entityManager->persist($post);
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

  #[Route('/sujet/new', name: 'new_sujet')]
  public function new_sujet(Request $request, EntityManagerInterface $entityManager): Response
  {
    // formulaire de nouveau sujet
    $sujet = new Sujet;

    $formNewSujet = $this->createForm(NewSujetType::class, $sujet);
    $formNewSujet->handleRequest($request);

    if ($formNewSujet->isSubmitted() && $formNewSujet->isValid()) {
      $sujet->setUser($this->getUser());

      $entityManager->persist($sujet);
      $entityManager->flush();
      return $this->redirectToRoute('sujet', ['titre' => $sujet->getTitre()]);
    }

    return $this->render('forum/newSujet.html.twig', [
      "page_title" => "Nouveau topic",
      "form" => $formNewSujet,
    ]);
  }
}
