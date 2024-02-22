<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateUserDTO
{
    public function __construct(
        #[Assert\NotBlank, Assert\Length(min: 3, max: 50)]
        public string $username,

        #[Assert\NotBlank]
        public string $password
    ) {
    }
}
