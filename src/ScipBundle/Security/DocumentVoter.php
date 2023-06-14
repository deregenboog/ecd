<?php

namespace ScipBundle\Security;

use ScipBundle\Entity\Document;
use ScipBundle\Service\DeelnemerDaoInterface;
use ScipBundle\Service\ToegangsrechtDaoInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DocumentVoter extends Voter
{
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * @var ToegangsrechtDaoInterface
     */
    private $toegangsrechtenDao;

    /**
     * @var DeelnemerDaoInterface
     */
    private $deelnemerDao;

    public function __construct(
        AccessDecisionManagerInterface $decisionManager,
        ToegangsrechtDaoInterface $toegangsrechtenDao,
        DeelnemerDaoInterface $deelnemerDao
    ) {
        $this->decisionManager = $decisionManager;
        $this->toegangsrechtenDao = $toegangsrechtenDao;
        $this->deelnemerDao = $deelnemerDao;
    }

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [Permissions::ACCESS])) {
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

        if ($this->decisionManager->decide($token, ['ROLE_SCIP_BEHEER'])) {
            return true;
        }

        $deelnemer = $this->deelnemerDao->findOneByDocument($subject);
        if (!$deelnemer) {
            return false;
        }

        $toegangsrecht = $this->toegangsrechtenDao->findOneByMedewerker($token->getUser());
        if (!$toegangsrecht) {
            return false;
        }

        foreach ($deelnemer->getProjecten() as $project) {
            if ($toegangsrecht->getProjecten()->contains($project)) {
                return true;
            }
        }

        return false;
    }
}
