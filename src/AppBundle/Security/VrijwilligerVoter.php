<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use AppBundle\Entity\Vrijwilliger;

class VrijwilligerVoter extends Voter
{
    // edit, create, view, delete
    public const USER_VRIJWILLIGER_ACTIONS = 'ROLE_USER_VRIJWILLIGER_ACTIONS';
    public const USER_VRIJWILLIGER_VIEW = 'ROLE_USER_VRIJWILLIGER_VIEW';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {

        if (!in_array($attribute, [
            self::USER_VRIJWILLIGER_ACTIONS,
            self::USER_VRIJWILLIGER_VIEW,
        ], true)) {
            return false;
        }

        // Allow null subject for USER_ACTIONS and USER_VIEW, as voteOnAttribute for these doesn't strictly depend on the subject instance.
        if (null !== $subject && !$subject instanceof Vrijwilliger) {
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

        /** @var Vrijwilliger $entity */
        $entity = $subject;

        switch ($attribute) {
            case self::USER_VRIJWILLIGER_ACTIONS:
                // Anyone with ROLE_VILLA_READ can view
                return $this->security->isGranted(self::USER_VRIJWILLIGER_ACTIONS);
            case self::USER_VRIJWILLIGER_VIEW:
                // Only users with ROLE_VILLA_CREATE can create
                return $this->security->isGranted(self::USER_VRIJWILLIGER_VIEW);
        }

        throw new \LogicException('This code should not be reached!');
    }
}