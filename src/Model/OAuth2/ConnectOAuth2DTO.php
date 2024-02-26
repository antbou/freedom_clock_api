<?php

namespace App\Model\OAuth2;

use Symfony\Component\Validator\Constraints as Assert;

final class ConnectOAuth2DTO
{
    private const ALLOWED_SERVICES = ['google'];

    public function __construct(
        #[Assert\NotBlank, Assert\Choice(choices: self::ALLOWED_SERVICES)]
        public string $service
    ) {
    }
}
