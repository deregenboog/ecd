<?php

namespace OekraineBundle\Security;

use OekraineBundle\Entity\Locatie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RegistratieVoter extends Voter
{
    private $vrijwilligersLocaties;

    public function __construct(array $vrijwilligersLocaties)
    {
        $this->vrijwilligersLocaties = $vrijwilligersLocaties;
    }

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [Permissions::REGISTER])) {
            return false;
        }

        if (!$subject instanceof Locatie) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        assert($subject instanceof Locatie);

        if (array_key_exists($token->getUsername(), $this->vrijwilligersLocaties)) {
            if ($subject->getId() != $this->vrijwilligersLocaties[$token->getUsername()]) {
                return false;
            }
        }

        return true;
    }
}
