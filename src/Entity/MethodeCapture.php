<?php

namespace App\Entity;

use App\Repository\MethodeCaptureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MethodeCaptureRepository::class)]
class MethodeCapture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomMethode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMethode(): ?string
    {
        return $this->nomMethode;
    }

    public function setNomMethode(string $nomMethode): static
    {
        $this->nomMethode = $nomMethode;

        return $this;
    }
}
