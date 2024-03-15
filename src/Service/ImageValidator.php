<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ImageValidator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(UploadedFile $file): ConstraintViolationListInterface
    {
        return $this->validator->validate($file, [
            new Assert\NotNull(),
            new Assert\File([
                'maxSize' => '1024k',
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                ],
                'mimeTypesMessage' => 'Please upload a valid image file',
            ])
        ]);
    }
}
