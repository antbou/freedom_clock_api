<?php

namespace App\Model\Quiz;

use App\Model\Pagination\PaginationDTO;
use Symfony\Component\Validator\Constraints as Assert;

final class QuizzesDTO
{
    public function __construct(
        #[Assert\Valid]
        public readonly ?PaginationDTO $pagination = null,

        #[Assert\All([
            new Assert\Uuid(),
            new Assert\NotBlank
        ])]
        public readonly ?array $ids = null,
    ) {
    }
}
