<?php

namespace ScipBundle\Security;

use ScipBundle\Entity\Deelnemer;
use ScipBundle\Service\ToegangsrechtDaoInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeelnemerVoter extends Voter
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

        if (!$subject instanceof Deelnemer) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        assert($subject instanceof Deelnemer);

        if ($this->decisionManager->decide($token, ['ROLE_SCIP_BEHEER'])) {
            return true;
        }

        $toegangsrecht = $this->toegangsrechtenDao->findOneByMedewerker($token->getUser());
        if (!$toegangsrecht) {
            return false;
        }

        foreach ($subject->getProjecten() as $project) {
            if ($toegangsrecht->getProjecten()->contains($project)) {
                return true;
            }
        }

        return false;
    }
}
