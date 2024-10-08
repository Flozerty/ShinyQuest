<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateMessage = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenuMessage = null;

    #[ORM\ManyToOne(inversedBy: 'messagesEnvoi')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userEnvoi = null;

    #[ORM\ManyToOne(inversedBy: 'messagesRecus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userRecoit = null;

    #[ORM\Column]
    private ?bool $lu = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Capture $pj = null;

    public function __construct()
    {
        // initialisation d'un nouveau message
        $this->dateMessage = new \DateTime();
        $this->lu = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateMessage(): ?\DateTimeInterface
    {
        return $this->dateMessage;
    }

    public function setDateMessage(\DateTimeInterface $dateMessage): static
    {
        $this->dateMessage = $dateMessage;

        return $this;
    }

    public function getContenuMessage(): ?string
    {
        return $this->contenuMessage;
    }

    public function setContenuMessage(string $contenuMessage): static
    {
        $this->contenuMessage = $contenuMessage;

        return $this;
    }

    public function getUserEnvoi(): ?User
    {
        return $this->userEnvoi;
    }

    public function setUserEnvoi(?User $userEnvoi): static
    {
        $this->userEnvoi = $userEnvoi;

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

    public function isLu(): ?bool
    {
        return $this->lu;
    }

    public function setLu(bool $lu): static
    {
        $this->lu = $lu;

        return $this;
    }

    public function getPj(): ?Capture
    {
        return $this->pj;
    }

    public function setPj(?Capture $pj): static
    {
        $this->pj = $pj;

        return $this;
    }
}
