<?php

namespace App\Entity;

use OpenApi\Attributes as OA;
use App\Entity\Enum\QuizStatus;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuizRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
#[HasLifecycleCallbacks]
class Quiz
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['quiz:read'])]
    #[OA\Property(type: 'string', format: 'uuid')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['quiz:read', 'quiz:write'])]
    #[Assert\NotBlank, Assert\Length(min: 3, max: 50)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['quiz:read', 'quiz:write'])]
    #[Assert\Length(min: 3, max: 50)]
    private ?string $introduction = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['quiz:read'])]
    private ?Image $image = null;

    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'quiz', orphanRemoval: true)]
    private Collection $questions;


    #[ORM\Column]
    #[Groups(['quiz:read'])]
    #[OA\Property(type: 'string')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['quiz:read'])]
    #[OA\Property(type: 'string', format: 'date-time')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'quizzesCreated')]
    #[Groups(['quiz:read'])]
    #[OA\Property(type: 'string', format: 'date-time')]
    private ?User $createdBy = null;

    #[ORM\OneToMany(targetEntity: Participant::class, mappedBy: 'quiz')]
    private Collection $participants;

    #[ORM\Column(type: "string", enumType: QuizStatus::class)]
    #[Groups(['quiz:read'])]
    private ?QuizStatus $status = null;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->status = QuizStatus::DRAFT;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction): static
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }


    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->setUpdatedAtValue();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setQuiz($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getQuiz() === $this) {
                $participant->setQuiz(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?QuizStatus
    {
        return $this->status;
    }

    public function setStatus(QuizStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
