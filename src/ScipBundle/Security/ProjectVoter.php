<?php

namespace ScipBundle\Security;

use ScipBundle\Entity\Project;
use ScipBundle\Service\ToegangsrechtDaoInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter
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

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [Permissions::ACCESS])) {
            return false;
        }

        if (!$subject instanceof Project) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        assert($subject instanceof Project);

        if ($this->decisionManager->decide($token, ['ROLE_SCIP_BEHEER'])) {
            return true;
        }

        $toegangsrecht = $this->toegangsrechtenDao->findOneByMedewerker($token->getUser());
        if (!$toegangsrecht) {
            return false;
        }

        if ($toegangsrecht->getProjecten()->contains($subject)) {
            return true;
        }

        return false;
    }
}
