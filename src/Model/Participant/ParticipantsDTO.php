<?php

namespace App\Model\Participant;

use App\Model\Pagination\PaginationDTO;
use Symfony\Component\Validator\Constraints as Assert;

final class ParticipantsDTO
{
    public function __construct(
        #[Assert\Valid]
        public readonly ?PaginationDTO $pagination = null
    ) {
    }
}
