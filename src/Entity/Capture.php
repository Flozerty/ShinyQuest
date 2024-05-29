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
}
