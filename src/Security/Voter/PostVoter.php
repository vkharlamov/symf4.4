<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, ['EDIT_POST', 'DELETE_POST'])
            && $subject instanceof Post;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Post $subject */
        switch ($attribute) {
            case 'EDIT_POST':
                return Post::STATUS_DRAFT_KEY === $subject->getStatus()
                    && $subject->getUserId() === $user->getId();

                break;

            case 'DELETE_POST':
                if ($subject->getUserId() === $user->getId()
                    && (
                        Post::STATUS_DECLINED_KEY === $subject->getStatus()
                        || Post::STATUS_PUBLISHED_KEY === $subject->getStatus()
                    )
                ) {
                    return true;
                }
                break;
        }

        return false;
    }
}
