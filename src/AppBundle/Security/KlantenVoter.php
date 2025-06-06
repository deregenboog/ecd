<?php

namespace AppBundle\Security;

use AppBundle\Entity\Klant;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use AppBundle\Entity\Klanten;
use HsBundle\Entity\Klus;

class KlantenVoter extends Voter
{
    // edit, create, view, delete
    public const USER_KLANTEN_ACTIONS = 'ROLE_USER_KLANTEN_ACTIONS';
    public const USER_KLANTEN_VIEW = 'ROLE_USER_KLANTEN_VIEW';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {

        // Ensure these match the defined constants in this class
        if (!in_array($attribute, [self::USER_KLANTEN_ACTIONS, self::USER_KLANTEN_VIEW], true)) {
            return false;
        }

        if (null !== $subject && !$subject instanceof Klant) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        /** @var Klant $entity */
        $entity = $subject;

        switch ($attribute) {
            case self::USER_KLANTEN_ACTIONS:
                // Check if user has the specific role for Klanten actions
                return $this->security->isGranted(self::USER_KLANTEN_ACTIONS);
            case self::USER_KLANTEN_VIEW:
                // Check if user has the specific role for Klanten view
                return $this->security->isGranted(self::USER_KLANTEN_VIEW);
        }

        throw new \LogicException('This code should not be reached!');
    }
}