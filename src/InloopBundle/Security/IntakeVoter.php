<?php

namespace InloopBundle\Security;

use InloopBundle\Entity\Intake;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class IntakeVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [Permissions::EDIT, Permissions::OWNER])) {
            return false;
        }

        if (!$subject instanceof Intake) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        assert($subject instanceof Intake);

        switch ($attribute) {
            case Permissions::EDIT:
                if (new \DateTime('-7 days') < $subject->getCreated()) {
                    return true;
                }
                break;
            case Permissions::OWNER:
                if ($token->getUser() == $subject->getMedewerker()) {
                    return true;
                }
                break;
            default:
                throw new \LogicException('Unsupported attribute '.$attribute);
        }

        return false;
    }
}
