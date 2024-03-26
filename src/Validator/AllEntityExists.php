<?php


namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class AllEntityExists extends Compound
{
    public function __construct(
        public string $type,
    ) {
        parent::__construct(options: []);
    }

    /**
     * @param mixed[] $options
     *
     * @return Constraint[]
     */
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\All([
                'constraints' => [
                    new Assert\NotNull(),
                    new EntityExists(entity: $this->type),
                ],
            ]),
        ];
    }
}
