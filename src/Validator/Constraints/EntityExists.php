<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityExists extends Constraint
{
    public function __construct(
        array $options = [],
        public ?string $entity = null,
        public string $message = 'The entity "{{ entity }}" with "{{ id }}" was not found in the database.',
    ) {
        $options['entity'] ??= $entity;
        $options['message'] ??= $message;

        parent::__construct($options);
    }

    public function getDefaultOption(): string
    {
        return 'entity';
    }

    public function getRequiredOptions(): array
    {
        return ['entity'];
    }
}
