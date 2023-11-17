<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ControllerAccessVoter extends Voter
{
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(
        AccessDecisionManagerInterface $decisionManager,
        RequestStack $requestStack
    ) {
        $this->decisionManager = $decisionManager;
        $this->requestStack = $requestStack;
    }

    protected function supports($attribute, $subject): bool
    {
        return 'CONTROLLER_ACCESS_VOTER' === $attribute;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $controller = $this->requestStack->getCurrentRequest()->get('_controller');
        switch ((string) $controller) {
            case 'error_controller::preview':
            case 'Symfony\Bundle\FrameworkBundle\Controller\RedirectController::redirectAction':
                return true;
            case '':
                throw new \InvalidArgumentException('Request has no controller.');
        }

        $controllerRole = $this->getControllerRole($controller);

        return (bool) $this->decisionManager->decide($token, [$controllerRole]);
    }

    private function getControllerRole($controller)
    {
        $matches = [];
        preg_match('/(.*)Bundle\\\Controller\\\(.*)Controller::.*/', $controller, $matches);

        if (3 !== count($matches)) {
            throw new \InvalidArgumentException('Could not determine controller role.');
        }

        return sprintf('CONTROLLER_%s_%s', strtoupper($matches[1]), strtoupper($matches[2]));
    }
}
