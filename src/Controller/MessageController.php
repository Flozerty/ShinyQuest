<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\AmisRepository;
use App\Repository\CaptureRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    // Page d'accueil de la messagerie
    #[Route('/messagerie', name: 'messagerie')]
    public function index(MessageRepository $messageRepository, AmisRepository $amisRepository): Response
    {
        $liensAmis = $amisRepository->getAllAmis($this->getUser());
        $amis = [];
        // récupération des amis depuis les Entités de lien d'amitié
        foreach ($liensAmis as $lien) {
            $lien->getUserDemande() == $this->getUser() ? $amis[] = $lien->getUserRecoit() : $amis[] = $lien->getUserDemande();
        }

        $messagerieData = [];

        foreach ($amis as $ami) {
            $dernierMessage = $messageRepository->getDernierMessage($this->getUser(), $ami);
            $newMessages = $messageRepository->getConversationNewMessages($this->getUser(), $ami);

            if ($newMessages) {
                $newMessages = $newMessages[0]["nb_messages"];
            } else {
                $newMessages = 0;
            }

            $messagerieData[] = [
                "ami" => $ami,
                "message" => $dernierMessage,
                "newMessages" => $newMessages
            ];
        }

        // dd($messagerieData);

        return $this->render('message/index.html.twig', [
            'page_title' => 'Messagerie',
            'messagerieData' => $messagerieData,
        ]);
    }

    // Page de conversation entre les 2 utilisateurs
    #[Route('/messagerie/{pseudo}', name: 'messages')]
    public function messages(MessageRepository $messageRepository, AmisRepository $amisRepository, EntityManagerInterface $entityManager, Request $request, CaptureRepository $captureRepository, User $ami, Capture $pjSend = null): Response
    {
        // vérification de si ami
        $relationship = $amisRepository->findFriendship($this->getUser(), $ami);
        if (isset($relationship[0]) && $relationship[0]->getStatut() == true) {

            $conversation = $messageRepository->getMessagesConversation($this->getUser(), $ami);

            $message = new Message();

            $formMessage = $this->createForm(MessageType::class, $message);
            $formMessage->handleRequest($request);

            // récupération de PJ si elle existe
            $pjSendId = $request->request->get('pjSend'); //  <=>   =  $_POST['pjSend']
            if ($pjSendId) {
                $pjSend = $captureRepository->findOneBy(['id' => $pjSendId]);
            }

            // validation du formulaire
            if ($formMessage->isSubmitted() && $formMessage->isValid()) {

                $pj = filter_input(INPUT_POST, "pj", FILTER_VALIDATE_INT);
                $capturePj = $captureRepository->findOneBy(['id' => $pj]);

                $message = $formMessage->getData(); //filter tous les inputs du FormType
                $message->setUserEnvoi($this->getUser());
                $message->setUserRecoit($ami);
                $message->setPj($capturePj);

                $entityManager->persist($message);
                $entityManager->flush();

                return $this->redirectToRoute('messages', ['pseudo' => $ami]);
            }

            foreach ($conversation as $message) {
                if ($message->getUserRecoit() == $this->getUser()) {
                    $message->setLu(true);
                    $entityManager->flush();
                }
            };

            return $this->render('message/conversation.html.twig', [
                // 'page_title' => 'Messages',
                'conversation' => $conversation,
                'ami' => $ami,
                'formMessage' => $formMessage,
                'pj' => $pjSend,
            ]);
        } else {
            // si pas ami
            $this->addFlash('danger', "Vous ne pouvez envoyer un message qu'à vos amis");
            return $this->redirectToRoute('app_home');
        }
    }

    // renvoie le nombre de messages non lus
    #[Route('/newMessages', name: 'new_messages')]
    public function getNewMessages(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->getAllNewMessages($this->getUser());

        return ($messages ? $this->json($messages[0]["nb_messages"]) : $this->json(0));
    }
}
