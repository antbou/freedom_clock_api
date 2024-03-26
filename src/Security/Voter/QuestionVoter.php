<?php

namespace App\Security\Voter;

use App\Entity\Quiz;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class QuestionVoter extends Voter
{
    public const CREATE = 'create';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE])
            && $subject instanceof Quiz;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        /**
         * @var Quiz $quiz
         */
        $quiz = $subject;

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::CREATE => $this->canCreate($quiz, $user),
            default => false,
        };
    }

    private function canCreate(Quiz $quiz, UserInterface $user): bool
    {
        // this assumes that the Quiz object has a `getCreatedBy()` method
        return $user === $quiz->getCreatedBy();
    }
}
