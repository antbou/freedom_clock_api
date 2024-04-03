<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;

#[\Attribute]
final class AllEntityExists extends Compound
{
    public function __construct(
        public readonly string $type,
    ) {
        parent::__construct(options: []);
    }

    protected function getConstraints(array $options): array
    {
        return [
            new Assert\All([
                'constraints' => [
                    new Assert\NotNull(),
                    new Assert\Uuid(),
                    new EntityExists(entity: $this->type),
                ],
            ]),
        ];
    }
}
