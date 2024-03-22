<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Attribute;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
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

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return ['entity'];
    }
}
