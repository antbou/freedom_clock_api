<?php

namespace App\Model\Question;

use App\Entity\QuestionType;
use App\Validator\BackedEnum;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateQuestionDTO
{
    public function __construct(
        #[Assert\NotNull, Assert\Length(max: 255)]
        public readonly string $text,

        #[Assert\NotNull]
        #[BackedEnum(enumType: QuestionType::class)]
        public readonly string $type,

        #[Assert\NotBlank]
        public readonly array $options = [],
    ) {
    }
}
