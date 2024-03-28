<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AnswerRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;


#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?Participant $participant = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?Option $selectedOption = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): static
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
