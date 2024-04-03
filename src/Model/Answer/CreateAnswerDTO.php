<?php

namespace App\Model\Answer;

use App\Entity\Option;

use Symfony\Component\Uid\Uuid;
use App\Validator\Constraints\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;


final class CreateAnswerDTO
{
    public function __construct(
        #[Assert\NotNull]
        #[EntityExists(entity: Option::class)]
        public readonly UUID $optionId
    ) {
    }
}
