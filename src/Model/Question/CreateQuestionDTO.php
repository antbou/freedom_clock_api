<?php

namespace App\Model\Question;

use App\Entity\Enum\QuestionType;
use App\Validator\BackedEnum;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateQuestionDTO
{
    public function __construct(
        #[Assert\Length(min: 3, max: 255)]
        public readonly string $text,

        #[Assert\NotBlank]
        #[BackedEnum(enumType: QuestionType::class)]
        public readonly string $type,
    ) {
    }
}
