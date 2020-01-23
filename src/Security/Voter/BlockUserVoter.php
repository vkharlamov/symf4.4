<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

class BlockUserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['BLOCK_USER'])
            && $subject instanceof User;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var  User $subject */
        switch ($attribute) {
            case 'BLOCK_USER':
                // For Admin area where user with ROLE_ADMIN
                // can not block by himself
                /** @var User $subject */
                if ($subject->getId() !== $user->getId()) {
                    return true; // i.e. block other users but not own
                }
                break;
            default:
                break;
        }

        return false;
    }
}
