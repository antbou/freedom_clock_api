<?php

namespace App\Model\Quiz;

use OpenApi\Attributes as OA;
use App\Model\Pagination\PaginationDTO;
use Symfony\Component\Validator\Constraints as Assert;

final class QuizzesDTO
{
    public function __construct(
        #[Assert\Valid]
        #[OA\Property(description: 'Pagination data', example: 'page=1&limit=10')]
        public readonly ?PaginationDTO $pagination = null,

        #[Assert\All([
            new Assert\Uuid(),
            new Assert\NotBlank
        ])]
        #[OA\Property(description: 'Array of quiz IDs', example: '["f7f1f1b1-1b1b-1b1b-1b1b-1b1b1b1b1b1b"]')]
        public readonly ?array $ids = null,
    ) {
    }
}
