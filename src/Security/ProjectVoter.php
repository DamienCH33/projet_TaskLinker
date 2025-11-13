<?php

namespace App\Security;

use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectVoter extends Voter
{
    public const VIEW = 'PROJECT_VIEW';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW])
            && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, $project, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        switch ($attribute) {
            case self::VIEW:
                return $project->getEmployees()->contains($user);
            default:
                return false;
        }
    }
}