<?php

namespace App\Serializer;

use App\Entity\User;
use App\View\UserView;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UserNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    public function normalize(mixed $user, string $format = null, array $context = []): array
    {
        $view = new UserView(
            $user->getId(),
            $user->getUsername(),
            $user->getRoles(),
            $user->getProvider()
        );

        return $this->normalizer->normalize($view, $format, $context);
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof User && $format === 'json';
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true,
        ];
    }
}
