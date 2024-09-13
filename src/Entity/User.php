<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PSEUDO', fields: ['pseudo'])]
// messages d'erreur pour les clés uniques
#[UniqueEntity(fields: ['email'], message: 'Adresse email déjà utilisée')]
#[UniqueEntity(fields: ['pseudo'], message: 'Pseudo déjà utilisé')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    /**
     * @var Collection<int, Capture>
     */
    #[ORM\OneToMany(targetEntity: Capture::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $captures;

    /**
     * @var Collection<int, Amis>
     */
    #[ORM\OneToMany(targetEntity: Amis::class, mappedBy: 'userDemande', orphanRemoval: true)]
    private Collection $amisDemande;

    /**
     * @var Collection<int, Amis>
     */
    #[ORM\OneToMany(targetEntity: Amis::class, mappedBy: 'userRecoit', orphanRemoval: true)]
    private Collection $amisRecoit;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'userEnvoi', orphanRemoval: true)]
    private Collection $messagesEnvoye;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'userRecoit', orphanRemoval: true)]
    private Collection $messagesRecus;

    /**
     * @var Collection<int, Sujet>
     */
    #[ORM\OneToMany(targetEntity: Sujet::class, mappedBy: 'user')]
    private Collection $sujets;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user')]
    private Collection $posts;

    #[ORM\Column]
    private ?bool $ban = null;

    // On donne la valeur par défaut de new DateTime dans le construct
    public function __construct()
    {
        $this->dateInscription = new \DateTime();
        $this->ban = false;

        $this->captures = new ArrayCollection();
        $this->amisDemande = new ArrayCollection();
        $this->amisRecoit = new ArrayCollection();
        $this->messagesEnvoye = new ArrayCollection();
        $this->messagesRecus = new ArrayCollection();
        $this->sujets = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function __toString()
    {
        return $this->getPseudo();
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
            $capture->setUser($this);
        }

        return $this;
    }

    public function removeCapture(Capture $capture): static
    {
        if ($this->captures->removeElement($capture)) {
            // set the owning side to null (unless already changed)
            if ($capture->getUser() === $this) {
                $capture->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Amis>
     */
    public function getAmisDemande(): Collection
    {
        return $this->amisDemande;
    }

    public function addAmisDemande(Amis $amisDemande): static
    {
        if (!$this->amisDemande->contains($amisDemande)) {
            $this->amisDemande->add($amisDemande);
            $amisDemande->setUserDemande($this);
        }

        return $this;
    }

    public function removeAmisDemande(Amis $amisDemande): static
    {
        if ($this->amisDemande->removeElement($amisDemande)) {
            // set the owning side to null (unless already changed)
            if ($amisDemande->getUserDemande() === $this) {
                $amisDemande->setUserDemande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Amis>
     */
    public function getAmisRecoit(): Collection
    {
        return $this->amisRecoit;
    }

    public function addAmisRecoit(Amis $amisRecoit): static
    {
        if (!$this->amisRecoit->contains($amisRecoit)) {
            $this->amisRecoit->add($amisRecoit);
            $amisRecoit->setUserRecoit($this);
        }

        return $this;
    }

    public function removeAmisRecoit(Amis $amisRecoit): static
    {
        if ($this->amisRecoit->removeElement($amisRecoit)) {
            // set the owning side to null (unless already changed)
            if ($amisRecoit->getUserRecoit() === $this) {
                $amisRecoit->setUserRecoit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesEnvoye(): Collection
    {
        return $this->messagesEnvoye;
    }

    public function addMessagesEnvoye(Message $messagesEnvoye): static
    {
        if (!$this->messagesEnvoye->contains($messagesEnvoye)) {
            $this->messagesEnvoye->add($messagesEnvoye);
            $messagesEnvoye->setUserEnvoi($this);
        }

        return $this;
    }

    public function removeMessagesEnvoye(Message $messagesEnvoye): static
    {
        if ($this->messagesEnvoye->removeElement($messagesEnvoye)) {
            // set the owning side to null (unless already changed)
            if ($messagesEnvoye->getUserEnvoi() === $this) {
                $messagesEnvoye->setUserEnvoi(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesRecus(): Collection
    {
        return $this->messagesRecus;
    }

    public function addMessagesRecu(Message $messagesRecu): static
    {
        if (!$this->messagesRecus->contains($messagesRecu)) {
            $this->messagesRecus->add($messagesRecu);
            $messagesRecu->setUserRecoit($this);
        }

        return $this;
    }

    public function removeMessagesRecu(Message $messagesRecu): static
    {
        if ($this->messagesRecus->removeElement($messagesRecu)) {
            // set the owning side to null (unless already changed)
            if ($messagesRecu->getUserRecoit() === $this) {
                $messagesRecu->setUserRecoit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sujet>
     */
    public function getSujets(): Collection
    {
        return $this->sujets;
    }

    public function addSujet(Sujet $sujet): static
    {
        if (!$this->sujets->contains($sujet)) {
            $this->sujets->add($sujet);
            $sujet->setUser($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): static
    {
        if ($this->sujets->removeElement($sujet)) {
            // set the owning side to null (unless already changed)
            if ($sujet->getUser() === $this) {
                $sujet->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    public function isBan(): ?bool
    {
        return $this->ban;
    }

    public function setBan(bool $ban): static
    {
        $this->ban = $ban;

        return $this;
    }
}
