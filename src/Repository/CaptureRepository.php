<?php

namespace App\Repository;

use App\Entity\Capture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Capture>
 */
class CaptureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Capture::class);
    }

    // toutes les captures terminées du pokemon par ID, par nb_rencontres DESC
    public function findCapturesByPokemonId(int $pokemonId): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.lieu as lieu', 'c.jeu as jeu', 'c.nbRencontres as nbRencontres', 'u.id as userId')
            ->join('c.user', 'u') // Association à l'entité User
            ->where('c.pokedexId = :pokemonId')
            ->andWhere('c.termine = 1')
            ->setParameter('pokemonId', $pokemonId)
            ->orderBy('c.nbRencontres', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // le jeu dans lequel le pokemon a été le plus capturé
    public function getNbCapturesByGame(int $pokedexId)
    {
        return $this->createQueryBuilder('c')
            ->select('c.jeu as jeu, COUNT(c.nomPokemon) as nb_captures')
            ->where('c.termine = 1')
            ->andWhere('c.pokedexId = :pokedexId')
            ->setParameter('pokedexId', $pokedexId)
            ->groupBy('c.jeu')
            ->orderBy('nb_captures', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    // Nb de captures par lieu pour un jeu et un pokemon donnés
    public function getPokemonCapturesByPlacesInGame(int $pokedexId, string $jeu)
    {
        return $this->createQueryBuilder('c')
            ->select('c.lieu as lieu, COUNT(c.nomPokemon) as nb_captures, AVG(c.nbRencontres) as moyenne_rencontres')
            ->where('c.termine = 1')
            ->andWhere('c.pokedexId = :pokedexId')
            ->andWhere('c.jeu = :jeu')
            ->setParameter('pokedexId', $pokedexId)
            ->setParameter('jeu', $jeu)
            ->groupBy('c.lieu')
            ->orderBy('nb_captures', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }
}
