<?php

namespace App\Controller;

use App\Repository\CaptureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
  #[Route('/', name: 'index_redirect')]
  #[Route('/home', name: 'app_home')]
  public function index(CaptureRepository $captureRepository, UserRepository $userRepository): Response
  {
    $user = $this->getUser();

    $nbCaptures = count($captureRepository->findBy(['termine' => 1]));
    $nbShassesActives = count($captureRepository->findBy(['termine' => 0, 'suivi' => 1]));
    $nbRencontresTotal = $captureRepository->getTotalNbRencontres()[0]['total'];
    $nbUsers = count($userRepository->findAll());
    if ($user) {
      $shasses = $captureRepository->findBy(['user' => $user, 'suivi' => 1]);

      $captures = $captureRepository->findBy(['termine' => 1], ["dateCapture" => "DESC"], 10);

      // return $this->render('home/index.html.twig', [
      return $this->render('home/homeDisconnected.html.twig', [
        // "page_title" => "Bienvenue sur SHINYQUEST",
        // "shasses" => $shasses,
        "captures" => $captures,
        "pika" => true,
        "nbCaptures" => $nbCaptures,
        "nbUsers" => $nbUsers,
        "nbShassesActives" => $nbShassesActives,
        "nbRencontresTotal" => $nbRencontresTotal,
      ]);
    } else {
      $captures = $captureRepository->findBy(['termine' => 1], ["dateCapture" => "DESC"], 10);

      return $this->render('home/homeDisconnected.html.twig', [
        "captures" => $captures,
        "pika" => true,
        "nbCaptures" => $nbCaptures,
        "nbUsers" => $nbUsers,
        "nbShassesActives" => $nbShassesActives,
        "nbRencontresTotal" => $nbRencontresTotal,
      ]);
    }
  }
}
