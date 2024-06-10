<?php

namespace App\Entity;

use App\Repository\CaptureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaptureRepository::class)]
class Capture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $surnom = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_rencontres = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCapture = null;

    #[ORM\Column]
    private ?bool $charmeChroma = null;

    #[ORM\Column]
    private ?bool $favori = null;

    #[ORM\Column]
    private ?int $pokedex_id = null;

    #[ORM\ManyToOne(inversedBy: 'captures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'captures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MethodeCapture $methodeCapture = null;

    #[ORM\Column]
    private ?bool $termine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurnom(): ?string
    {
        return $this->surnom;
    }

    public function setSurnom(?string $surnom): static
    {
        $this->surnom = $surnom;

        return $this;
    }

    public function getNbRencontres(): ?int
    {
        return $this->nb_rencontres;
    }

    public function setNbRencontres(?int $nb_rencontres): static
    {
        $this->nb_rencontres = $nb_rencontres;

        return $this;
    }

    public function getDateCapture(): ?\DateTimeInterface
    {
        return $this->dateCapture;
    }

    public function setDateCapture(?\DateTimeInterface $dateCapture): static
    {
        $this->dateCapture = $dateCapture;

        return $this;
    }

    public function isCharmeChroma(): ?bool
    {
        return $this->charmeChroma;
    }

    public function setCharmeChroma(bool $charmeChroma): static
    {
        $this->charmeChroma = $charmeChroma;

        return $this;
    }

    public function isFavori(): ?bool
    {
        return $this->favori;
    }

    public function setFavori(bool $favori): static
    {
        $this->favori = $favori;

        return $this;
    }

    public function getPokedexId(): ?int
    {
        return $this->pokedex_id;
    }

    public function setPokedexId(int $pokedex_id): static
    {
        $this->pokedex_id = $pokedex_id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMethodeCapture(): ?MethodeCapture
    {
        return $this->methodeCapture;
    }

    public function setMethodeCapture(?MethodeCapture $methodeCapture): static
    {
        $this->methodeCapture = $methodeCapture;

        return $this;
    }

    public function isTermine(): ?bool
    {
        return $this->termine;
    }

    public function setTermine(bool $termine): static
    {
        $this->termine = $termine;

        return $this;
    }
}
