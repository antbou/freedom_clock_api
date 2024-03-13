<?php

namespace App\Model\Quiz;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateQuizDTO
{
    public function __construct(
        #[Assert\NotBlank, Assert\Length(min: 3, max: 50)]
        public string $title,

        #[Assert\Length(min: 3, max: 255)]
        public ?string $introduction,
    ) {
    }
}
