<?php

namespace ScipBundle\Security;

use ScipBundle\Entity\Deelname;
use ScipBundle\Service\ToegangsrechtDaoInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeelnameVoter extends Voter
{
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * @var ToegangsrechtDaoInterface
     */
    private $toegangsrechtenDao;

    public function __construct(
        AccessDecisionManagerInterface $decisionManager,
        ToegangsrechtDaoInterface $toegangsrechtenDao
    ) {
        $this->decisionManager = $decisionManager;
        $this->toegangsrechtenDao = $toegangsrechtenDao;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [Permissions::ACCESS])) {
            return false;
        }

        if (!$subject instanceof Deelname) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        assert($subject instanceof Deelname);

        if ($this->decisionManager->decide($token, ['ROLE_SCIP_BEHEER'])) {
            return true;
        }

        $toegangsrecht = $this->toegangsrechtenDao->findOneByMedewerker($token->getUser());
        if (!$toegangsrecht) {
            return false;
        }

        if ($toegangsrecht->getProjecten()->contains($subject->getProject())) {
            return true;
        }

        return false;
    }
}
