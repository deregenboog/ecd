<?php

namespace AppBundle\Security;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class DoelstellingVoter extends Voter
{
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    private $vrijwilligersLocaties;
    public const EDIT = 'edit';

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }
        else if(!is_string($subject))
        {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        /** @var User $user */
        $user = $token->getUser();
        $roles = $token->getRoles();


        //list($class,$method) = explode("::",$subject);
        $roleName = $this->getRoleNameForRepositoryMethod($subject);

        if ($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }
        if ($this->decisionManager->decide($token, ['ROLE_DOELSTELLING_BEHEER'])) {
            return true;
        }
        if ($this->decisionManager->decide($token, [$roleName])) {
            return true;
        }
        return false;
    }

    private function getBundleName($repositoryMethodString)
    {
        $matches = [];
        $re = '/(.*)Bundle\\\\(.*)\\\\(.*)::(.*)/';
        preg_match($re, $repositoryMethodString, $matches);

        if (5 !== count($matches)) {
            throw new \BadFunctionCallException('Could not determine proper bundle name. Should provide valid repository methodstring: Namespace\BundleName\Class::Method');
        }

        return sprintf('%s', strtoupper($matches[1]));
    }

    public function getRoleNameForRepositoryMethod($repositoryMethodString)
    {
        return "ROLE_".$this->getBundleName($repositoryMethodString);//."_BEHEER";
    }
}
