<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

final class UsersDTO
{
    public function __construct(
        #[Assert\Valid]
        public readonly ?Pagination $pagination = null,

        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\NotBlank,
            new Assert\Positive
        ])]
        public readonly ?array $ids = null,
    ) {
    }
}
