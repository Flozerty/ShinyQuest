<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\User;
use App\Form\CaptureType;
use App\Form\ShasseStartType;
use App\HttpClient\ApiHttpClient;
use App\Repository\AmisRepository;
use App\Repository\CaptureRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function PHPUnit\Framework\isEmpty;

class CaptureController extends AbstractController
{
  // Toutes mes captures
  #[Route('/user/{pseudo}/captures', name: 'captures')]
  public function captures(User $user, CaptureRepository $captureRepository, AmisRepository $amisRepository): Response
  {
    if (!$user) {
      return $this->redirectToRoute('app_login');
    }

    $captures = $captureRepository->findBy(['user' => $user, 'termine' => 1], ['dateCapture' => "DESC"]);

    // récupération des amis
    $amisData = $amisRepository->getAllAmis($this->getUser());
    $amis = [];

    foreach ($amisData as $ami) {
      $amis[] = $ami->getUserRecoit() == $this->getUser() ? $ami->getUserDemande() : $ami->getUserRecoit();
    }
    // dd($amis);

    return $this->render('capture/captures.html.twig', [
      "page_title" => $this->getUser() == $user ? 'Mes captures' : "Captures de " . $user->getPseudo(),
      "captures" => $captures,
      "user" => $user,
      "amis" => $amis,
    ]);
  }

  // Shasses en cours
  #[Route('/shasses', name: 'my_shasses')]
  public function shasses(ApiHttpClient $apiHttpClient, CaptureRepository $captureRepository, Request $request, EntityManagerInterface $entityManager): Response
  {
    $user = $this->getUser();
    if (!$user) {
      return $this->redirectToRoute('app_home');
    }

    $shasse = new Capture;

    //////////////////////////  formulaire de nouvelle shasse  //////////////////////////
    $formNewShasse = $this->createForm(ShasseStartType::class, $shasse);
    $formNewShasse->handleRequest($request);

    // validation du formulaire de nouvelle shasse
    if ($formNewShasse->isSubmitted() && $formNewShasse->isValid()) {

      // vérifie que l'utilisateur remplit bien le formulaire depuis le site. 
      if (isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], '127.0.0.1:8000')) {
        // vérification honey pot
        if (isset($_POST["email"]) && empty($_POST["email"])) {

          $pokemonId = filter_input(INPUT_POST, "pokemonId", FILTER_VALIDATE_INT);
          $jeu = filter_input(INPUT_POST, "jeu", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

          // on récupère le nom du pokemon, son sprite, etc.
          $pokemon = $apiHttpClient->getPokemonInfos($pokemonId);
          $nomPokemon = $apiHttpClient->getPokemonNameById($pokemonId);

          $shasse = $formNewShasse->getData(); //filter tous les inputs du FormType

          $nbRencontres = $formNewShasse->get('nbRencontres')->getData();
          $shasse->setNbRencontres(empty($nbRencontres) ? 0 : $nbRencontres);

          $shasse->setSuivi(true);
          $shasse->setPokedexId($pokemonId);
          $shasse->setImgShiny($pokemon["pkmnStats"]["sprites"]["other"]["official-artwork"]["front_shiny"]);
          $shasse->setNomPokemon($nomPokemon);
          $shasse->setJeu($jeu);
          $shasse->setUser($user);

          // prepare() and execute()
          $entityManager->persist($shasse);
          $entityManager->flush();

          return $this->redirectToRoute('my_shasses');
        } else {
          return $this->redirectToRoute('bot_detected');
        }
      } else {
        return $this->redirectToRoute('bot_detected');
      }
    }

    //////////////////////////  formulaire de shasse terminée //////////////////////////
    $formCapture = $this->createForm(CaptureType::class, $shasse);
    $formCapture->handleRequest($request);
    
    // validation du formulaire de shiny trouvé
    if ($formCapture->isSubmitted() && $formCapture->isValid()) {

      // vérifie que l'utilisateur remplit bien le formulaire depuis le site. 
      if (isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], '127.0.0.1:8000')) {
        // vérification honey pot
        if (isset($_POST["email"]) && empty($_POST["email"])) {

          $idCapture = filter_input(INPUT_POST, "IdCapture", FILTER_VALIDATE_INT);
          $ball = filter_input(INPUT_POST, "ball", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

          // on va récupérer la capture avec cet ID.
          $capture = $captureRepository->find($idCapture);

          // et on lui donne les résultats du formulaire de modal :
          $capture->setSurnom($shasse->getSurnom());
          $capture->setDateCapture($shasse->getDateCapture());
          $capture->setSexe($shasse->getSexe());

          $capture->setBall($ball);

          $capture->setSuivi(false);
          $capture->setTermine(true);

          $entityManager->flush();

          $this->addFlash('success', 'Félicitations, vous avez capturé ' . $capture->getnomPokemon());
        } else {
          return $this->redirectToRoute('bot_detected');
        }
      } else {
        return $this->redirectToRoute('bot_detected');
      }
    }

    $captures = $captureRepository->findBy(['user' => $user, 'termine' => 0]);

    return $this->render('capture/shasses.html.twig', [
      "page_title" => 'Mes shasses',
      "captures" => $captures,
      "formCapture" => $formCapture,
      "formNewShasse" => $formNewShasse,
      "allPokemons" => [],
      "balls" => [],
    ]);
  }

  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////

  // supprimer une shasses
  #[Route('/shasse/{id}/delete', name: 'delete_shasse')]
  public function deleteShasse(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if (!(isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], '//127.0.0.1:8000'))) {
      return $this->redirectToRoute('bot_detected');
    }

    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $entityManager->remove($shasse);
      $entityManager->flush();
    }

    return $this->redirectToRoute("my_shasses");
  }

  // supprimer une capture
  #[Route('/capture/{id}/delete', name: 'delete_capture')]
  public function deleteCapture(Capture $shasse = null, EntityManagerInterface $entityManager, MessageRepository $messageRepository, CaptureRepository $captureRepository): Response
  {
    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {

      $fakeCapture = $captureRepository->findOneBy(["id" => 1]);
      // vérification de l'existance de messages avec la capture en PJ & cahngement avec la capture factice.
      $messages = $messageRepository->getMessagesWithPj($shasse);
      foreach ($messages as $message) {
        $message->setPj($fakeCapture);
        $entityManager->flush();
      }

      $entityManager->remove($shasse);
      $entityManager->flush();
    }

    return $this->redirectToRoute('captures', ['pseudo' => $this->getUser()]);
  }

  // retirer du suivi des shasses
  #[Route('/shasse/{id}/stop_suivi', name: 'stop_suivi')]
  public function stopSuivi(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $shasse->setSuivi(false);
      $entityManager->flush();
    }

    return $this->redirectToRoute("my_shasses");
  }

  // ajouter au suivi des shasses
  #[Route('/shasse/{id}/add_suivi', name: 'add_suivi')]
  public function addSuivi(Capture $shasse = null, EntityManagerInterface $entityManager): Response
  {
    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      $shasse->setSuivi(true);
      $entityManager->flush();
    }
    return $this->redirectToRoute("my_shasses");
  }

  // update counter input
  #[Route('/shasse/{id}/updateCounter/{newValue}', name: 'shasse_updateCounter')]
  public function updateCounter(int $id, int $newValue, EntityManagerInterface $entityManager, CaptureRepository $captureRepository): Response
  {
    $shasse = $captureRepository->find($id);

    if ($shasse && ($shasse->getUser() == $this->getUser() || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
      if ($newValue >= 0) {
        $shasse->setNbRencontres($newValue);
        $entityManager->flush();
      } else {
        $shasse->setNbRencontres(0);
        $entityManager->flush();
      }
    }

    return $this->json(['nbRencontres' => $shasse->getNbRencontres()]);
  }
}
