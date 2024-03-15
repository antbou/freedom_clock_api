<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Image;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class ImageVoter extends Voter
{
    public const UPDATE = 'update';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::UPDATE])
            && $subject instanceof Image;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Image $image */
        $image = $subject;

        return match ($attribute) {
            self::UPDATE => $this->canUpdate($image, $user),
            default => false,
        };
    }

    private function canUpdate(Image $image, User $user): bool
    {
        return $user === $image->getCreatedBy();
    }
}
