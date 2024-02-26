<?php

namespace App\Model\User;

use App\Model\Pagination\PaginationDTO;
use Symfony\Component\Validator\Constraints as Assert;

final class UsersDTO
{
    public function __construct(
        #[Assert\Valid]
        public readonly ?PaginationDTO $pagination = null,

        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\NotBlank,
            new Assert\Positive
        ])]
        public readonly ?array $ids = null,
    ) {
    }
}
