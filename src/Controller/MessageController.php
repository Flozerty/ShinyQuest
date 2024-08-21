<?php

namespace App\Controller;

use App\Repository\AmisRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    #[Route('/messages', name: 'messagerie')]
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
                $ami,
                $dernierMessage,
            ];
        }

        return $this->render('message/index.html.twig', [
            'page_title' => 'Messagerie',
            'messagerieData' =>  $messagerieData,
        ]);
    }
}
