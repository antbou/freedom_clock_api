<?php

namespace App\Model\Image;

use Symfony\Component\Validator\Constraints as Assert;

final class ImageDTO
{
    public function __construct(
        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\NotBlank,
            new Assert\Positive
        ])]
        public readonly ?array $ids = null,
    ) {
    }
}
