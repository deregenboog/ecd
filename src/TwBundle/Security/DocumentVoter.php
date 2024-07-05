<?php

namespace TwBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use TwBundle\Entity\Document;

class DocumentVoter extends Voter
{
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [Permissions::OWNER])) {
            return false;
        }

        if (!$subject instanceof Document) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        assert($subject instanceof Document);

        if ($this->decisionManager->decide($token, ['ROLE_ADMIN']) || $this->decisionManager->decide($token, ['ROLE_TW_BEHEER'])) {
            return true;
        }

        switch ($attribute) {
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
