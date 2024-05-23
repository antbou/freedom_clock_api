<?php

namespace App\Model\Participant;

use OpenApi\Attributes as OA;
use App\Model\Pagination\PaginationDTO;
use Symfony\Component\Validator\Constraints as Assert;

final class ParticipantsDTO
{
    public function __construct(
        #[Assert\Valid]
        #[OA\Property(description: 'Pagination data', example: 'page=1&limit=10')]
        public readonly ?PaginationDTO $pagination = null
    ) {
    }
}
