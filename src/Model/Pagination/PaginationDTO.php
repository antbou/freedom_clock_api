<?php

namespace App\Model\Pagination;

use Symfony\Component\Validator\Constraints as Assert;

final class PaginationDTO
{
    public function __construct(
        #[Assert\NotBlank, Assert\Positive]
        public readonly int $page,
        #[Assert\NotBlank, Assert\Positive]
        public readonly int $limit
    ) {
    }
}
