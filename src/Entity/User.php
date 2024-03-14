<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Provider = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $provider_id = null;

    #[ORM\OneToMany(targetEntity: Quiz::class, mappedBy: 'createdBy')]
    private Collection $quizzesCreated;

    #[ORM\OneToMany(targetEntity: QuizParticipant::class, mappedBy: 'participant')]
    private Collection $quizzesParticipated;

    public function __construct()
    {
        $this->quizzesCreated = new ArrayCollection();
        $this->quizzesParticipated = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

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

    public function getProvider(): ?string
    {
        return $this->Provider;
    }

    public function setProvider(?string $Provider): static
    {
        $this->Provider = $Provider;

        return $this;
    }

    public function getProviderId(): ?string
    {
        return $this->provider_id;
    }

    public function setProviderId(?string $provider_id): static
    {
        $this->provider_id = $provider_id;

        return $this;
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuizzesCreated(): Collection
    {
        return $this->quizzesCreated;
    }

    public function addQuizzesCreated(Quiz $quizzesCreated): static
    {
        if (!$this->quizzesCreated->contains($quizzesCreated)) {
            $this->quizzesCreated->add($quizzesCreated);
            $quizzesCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removeQuizzesCreated(Quiz $quizzesCreated): static
    {
        if ($this->quizzesCreated->removeElement($quizzesCreated)) {
            // set the owning side to null (unless already changed)
            if ($quizzesCreated->getCreatedBy() === $this) {
                $quizzesCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuizParticipant>
     */
    public function getQuizzesParticipated(): Collection
    {
        return $this->quizzesParticipated;
    }

    public function addQuizzesParticipated(QuizParticipant $quizzesParticipated): static
    {
        if (!$this->quizzesParticipated->contains($quizzesParticipated)) {
            $this->quizzesParticipated->add($quizzesParticipated);
            $quizzesParticipated->setParticipant($this);
        }

        return $this;
    }

    public function removeQuizzesParticipated(QuizParticipant $quizzesParticipated): static
    {
        if ($this->quizzesParticipated->removeElement($quizzesParticipated)) {
            // set the owning side to null (unless already changed)
            if ($quizzesParticipated->getParticipant() === $this) {
                $quizzesParticipated->setParticipant(null);
            }
        }

        return $this;
    }
}
