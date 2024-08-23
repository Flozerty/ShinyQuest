<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AmisRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
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
            $messagerieData[] = [
                "ami" => $ami,
                "message" => $dernierMessage,
            ];
        }
        // dd($messagerieData);

        return $this->render('message/index.html.twig', [
            'page_title' => 'Messagerie',
            'messagerieData' =>  $messagerieData,
        ]);
    }

    #[Route('/messagerie/{pseudo}', name: 'messages')]
    public function messages(MessageRepository $messageRepository, AmisRepository $amisRepository, User $ami): Response
    {
        $relationship = $amisRepository->findFriendship($this->getUser(), $ami);
        // dd($relationship[0]->getStatut());
        
        if (isset($relationship[0]) && $relationship[0]->getStatut() == true) {

            $conversation = $messageRepository->getMessagesConversation($this->getUser(), $ami);

            return $this->render('message/conversation.html.twig', [
                // 'page_title' => 'Messages',
                'conversation' =>  $conversation,
                'ami' => $ami,
            ]);
        } else {
            $this->addFlash('danger', "Vous ne pouvez envoyer un message qu'à vos amis");
            return $this->redirectToRoute('app_home');
        }
    }
}
