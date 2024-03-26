<?php

namespace App\Model\Option;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateOptionDTO
{
    public function __construct(
        #[Assert\NotBlank, Assert\Length(max: 255)]
        public readonly ?string $text = null,

        #[Assert\NotNull, Assert\Type('bool')]
        public readonly bool $isCorrect = false,
    ) {
    }
}
