<?php

namespace App\Model\Quiz;

use App\Entity\Enum\QuizStatus;
use App\Validator\BackedEnum;
use Symfony\Component\Validator\Constraints as Assert;

final class StatusQuizDTO
{
    public function __construct(
        #[Assert\NotNull]
        #[BackedEnum(enumType: QuizStatus::class)]
        public readonly string $status,
    ) {
    }
}
