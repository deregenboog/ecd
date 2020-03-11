<?php

namespace Tests\AppBundle\Security;

use AppBundle\Security\ControllerAccessVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ControllerAccessVoterTest extends TestCase
{
    public function testVoteOnUnsupportedAttribute()
    {
        $token = $this->getMockForAbstractClass(TokenInterface::class);
        $token->method('getRoleNames')->willReturn(['CONTROLLER_APP_KLANTEN']);

        $decisionManager = $this->getMockForAbstractClass(AccessDecisionManagerInterface::class);

        $request = $this->createMock(Request::class);
        $request->method('get')->with('_controller')
            ->willReturn('Symfony\Bundle\FrameworkBundle\Controller\RedirectController::redirectAction');
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $voter = new ControllerAccessVoter($decisionManager, $requestStack);

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, new \stdClass(), ['UNSUPPORTED_ATTRIBUTE']));
    }

    public function testVoteOnRedirectController()
    {
        $token = $this->getMockForAbstractClass(TokenInterface::class);
        $token->method('getRoleNames')->willReturn(['CONTROLLER_APP_KLANTEN']);

        $decisionManager = $this->getMockForAbstractClass(AccessDecisionManagerInterface::class);

        $request = $this->createMock(Request::class);
        $request->method('get')->with('_controller')
            ->willReturn('Symfony\Bundle\FrameworkBundle\Controller\RedirectController::redirectAction');
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $voter = new ControllerAccessVoter($decisionManager, $requestStack);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, new \stdClass(), ['CONTROLLER_ACCESS_VOTER']));
    }

    public function testVoteOnGrantedController()
    {
        $token = $this->getMockForAbstractClass(TokenInterface::class);
        $token->method('getRoleNames')->willReturn(['CONTROLLER_APP_KLANTEN']);

        $decisionManager = $this->getMockForAbstractClass(AccessDecisionManagerInterface::class);
        $decisionManager->expects($this->once())->method('decide')->with($token, ['CONTROLLER_APP_KLANTEN']);

        $request = $this->createMock(Request::class);
        $request->method('get')->with('_controller')
            ->willReturn('AppBundle\Controller\KlantenController::indexAction');
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $voter = new ControllerAccessVoter($decisionManager, $requestStack);
        $voter->vote($token, new \stdClass(), ['CONTROLLER_ACCESS_VOTER']);
    }

    public function testVoteOnDeniedController()
    {
        $token = $this->getMockForAbstractClass(TokenInterface::class);
        $token->method('getRoleNames')->willReturn(['CONTROLLER_APP_KLANTEN']);

        $decisionManager = $this->getMockForAbstractClass(AccessDecisionManagerInterface::class);

        $request = $this->createMock(Request::class);
        $request->method('get')->with('_controller')
            ->willReturn('AppBundle\Controller\SomeOtherController::indexAction');
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $voter = new ControllerAccessVoter($decisionManager, $requestStack);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, new \stdClass(), ['CONTROLLER_ACCESS_VOTER']));
    }
}
