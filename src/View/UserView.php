<?php

namespace App\View;

final class UserView
{
    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly array $roles,
        public readonly ?string $provider
    ) {
    }
}
