<?php

namespace App\Entity;

use App\Repository\AmisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmisRepository::class)]
class Amis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'amisDemande')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userDemande = null;

    #[ORM\ManyToOne(inversedBy: 'amisRecoit')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userRecoit = null;

    #[ORM\Column(length: 100)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDemande = null;


    // initialisation des valeurs
    public function __construct()
    {
        $this->dateDemande = new \DateTime();
        $this->statut = "en attente";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserDemande(): ?User
    {
        return $this->userDemande;
    }

    public function setUserDemande(?User $userDemande): static
    {
        $this->userDemande = $userDemande;

        return $this;
    }

    public function getUserRecoit(): ?User
    {
        return $this->userRecoit;
    }

    public function setUserRecoit(?User $userRecoit): static
    {
        $this->userRecoit = $userRecoit;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTimeInterface $dateDemande): static
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }
}
