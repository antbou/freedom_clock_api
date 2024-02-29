<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?QuizParticipant $participant = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?Option $selectedOption = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipant(): ?QuizParticipant
    {
        return $this->participant;
    }

    public function setParticipant(?QuizParticipant $participant): static
    {
        $this->participant = $participant;

        return $this;
    }

    public function getSelectedOption(): ?Option
    {
        return $this->selectedOption;
    }

    public function setSelectedOption(?Option $selectedOption): static
    {
        $this->selectedOption = $selectedOption;

        return $this;
    }
}
