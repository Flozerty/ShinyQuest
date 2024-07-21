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
    private ?int $nbRencontres = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCapture = null;

    #[ORM\Column]
    private ?bool $charmeChroma = null;

    #[ORM\Column]
    private ?bool $favori = null;

    #[ORM\Column]
    private ?int $pokedexId = null;

    #[ORM\ManyToOne(inversedBy: 'captures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'captures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MethodeCapture $methodeCapture = null;

    #[ORM\Column]
    private ?bool $termine = null;

    #[ORM\Column(length: 255)]
    private ?string $imgShiny = null;

    #[ORM\Column(length: 50)]
    private ?string $nomPokemon = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $jeu = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $sexe = null;

    #[ORM\Column]
    private ?bool $suivi = null;

    #[ORM\Column(nullable: true)]
    private ?string $ball = null;

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
        return $this->nbRencontres;
    }

    public function setNbRencontres(?int $nbRencontres): static
    {
        $this->nbRencontres = $nbRencontres;

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
        return $this->pokedexId;
    }

    public function setPokedexId(int $pokedexId): static
    {
        $this->pokedexId = $pokedexId;

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

    public function getTermine(): ?bool
    {
        return $this->termine;
    }

    public function setTermine(bool $termine): static
    {
        $this->termine = $termine;

        return $this;
    }

    public function getImgShiny(): ?string
    {
        return $this->imgShiny;
    }

    public function setImgShiny(string $imgShiny): static
    {
        $this->imgShiny = $imgShiny;

        return $this;
    }

    public function getNomPokemon(): ?string
    {
        return $this->nomPokemon;
    }

    public function setNomPokemon(string $nomPokemon): static
    {
        $this->nomPokemon = $nomPokemon;

        return $this;
    }

    public function getJeu(): ?string
    {
        return $this->jeu;
    }

    public function setJeu(string $jeu): static
    {
        $this->jeu = $jeu;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function isSuivi(): ?bool
    {
        return $this->suivi;
    }

    public function setSuivi(bool $suivi): static
    {
        $this->suivi = $suivi;

        return $this;
    }

    public function getBall(): ?string
    {
        return $this->ball;
    }

    public function setBall(string $ball): static
    {
        $this->ball = $ball;

        return $this;
    }
}
