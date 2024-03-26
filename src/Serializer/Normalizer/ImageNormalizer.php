<?php

namespace App\Serializer\Normalizer;

use App\Entity\Image;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ImageNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private UploaderHelper $uploaderHelper
    ) {
    }

    /**
     * @param Image $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $url = $this->uploaderHelper->asset($object, 'file');

        $object->setUrl($url);

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Image;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Image::class => true];
    }
}
