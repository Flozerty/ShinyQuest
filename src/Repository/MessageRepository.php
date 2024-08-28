<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getMessagerieUser(User $user)
    {
        return $this->createQueryBuilder('m')
            ->where('m.userEnvoi = :user')
            ->orWhere('m.userRecoit = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
    public function getMessagesConversation(User $user, User $user2)
    {
        return $this->createQueryBuilder('m')
            ->where('(m.userEnvoi = :user AND m.userRecoit = :user2)')
            ->orWhere('(m.userRecoit = :user AND m.userEnvoi = :user2)')
            ->setParameter('user', $user)
            ->setParameter('user2', $user2)
            ->orderBy('m.dateMessage', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getDernierMessage(User $user, User $user2)
    {
        return $this->createQueryBuilder('m')
            ->where('(m.userEnvoi = :user AND m.userRecoit = :user2)')
            ->orWhere('(m.userRecoit = :user AND m.userEnvoi = :user2)')
            ->setParameter('user', $user)
            ->setParameter('user2', $user2)
            ->orderBy('m.dateMessage', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function getAllNewMessages(User $user)
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.lu) as nb_messages')
            ->where('m.userRecoit = :user')
            ->andWhere('m.lu = 0')
            ->setParameter('user', $user)
            ->groupBy('m.lu')
            ->getQuery()
            ->getResult();
    }

    public function getConversationNewMessages(User $user, User $user2)
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.lu) as nb_messages')
            ->where('m.userRecoit = :user AND m.userEnvoi = :user2')
            ->andWhere('m.lu = 0')
            ->setParameter('user', $user)
            ->setParameter('user2', $user2)
            ->groupBy('m.lu')
            ->getQuery()
            ->getResult();
    }
}
