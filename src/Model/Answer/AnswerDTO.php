<?php

namespace App\Model\Answer;

use App\Entity\Participant;
use App\Validator\Constraints\AllEntityExists;
use Symfony\Component\Validator\Constraints as Assert;

final class AnswerDTO
{
    public function __construct(
        #[Assert\Count(min: 1), AllEntityExists(Participant::class)]
        public readonly array $participantIds = []
    ) {
    }
}
