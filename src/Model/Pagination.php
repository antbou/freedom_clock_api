<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

final class Pagination
{
    public function __construct(
        #[Assert\NotBlank, Assert\Positive]
        public readonly int $page,
        #[Assert\NotBlank, Assert\Positive]
        public readonly int $limit
    ) {
    }
}
