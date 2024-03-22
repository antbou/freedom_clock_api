<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Attribute;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class BackedEnum extends Constraint
{
    final public const NO_SUCH_CASE_ERROR = '8e6b7234-171a-4389-a4fc-9324586a6869';

    public function __construct(
        public string $enumType,
        public bool $multiple = false,
        public ?string $message = null,
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
