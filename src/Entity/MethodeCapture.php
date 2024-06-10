<?php

namespace App\Entity;

use App\Repository\MethodeCaptureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Capture>
     */
    #[ORM\OneToMany(targetEntity: Capture::class, mappedBy: 'methodeCapture')]
    private Collection $captures;

    public function __construct()
    {
        $this->captures = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Capture>
     */
    public function getCaptures(): Collection
    {
        return $this->captures;
    }

    public function addCapture(Capture $capture): static
    {
        if (!$this->captures->contains($capture)) {
            $this->captures->add($capture);
            $capture->setMethodeCapture($this);
        }

        return $this;
    }

    public function removeCapture(Capture $capture): static
    {
        if ($this->captures->removeElement($capture)) {
            // set the owning side to null (unless already changed)
            if ($capture->getMethodeCapture() === $this) {
                $capture->setMethodeCapture(null);
            }
        }

        return $this;
    }
}
