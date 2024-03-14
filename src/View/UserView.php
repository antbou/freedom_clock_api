<?php

namespace App\View;

use Symfony\Component\Uid\Uuid;

final class UserView
{
    public function __construct(
        public readonly Uuid $id,
        public readonly string $username,
        public readonly array $roles,
        public readonly ?string $provider
    ) {
    }
}
