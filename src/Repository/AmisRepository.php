<?php

namespace App\Repository;

use App\Entity\Amis;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Amis>
 */
class AmisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amis::class);
    }

    public function getAllAmis(User $user)
    {
        return $this->createQueryBuilder('a')
            ->where('(a.userDemande = :user OR a.userRecoit = :user)')
            ->andWhere('a.statut = 1')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findFriendship(User $user, User $user2)
    {
        return $this->createQueryBuilder('a')
            ->where('(a.userDemande = :user AND a.userRecoit = :user2)')
            ->orwhere('(a.userRecoit = :user AND a.userDemande = :user2)')
            ->setParameter('user', $user)
            ->setParameter('user2', $user2)
            ->getQuery()
            ->getResult();
    }
}
