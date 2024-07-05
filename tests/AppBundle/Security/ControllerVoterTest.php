<?php

declare(strict_types=1);

namespace Tests\AppBundle\Security;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class ControllerVoterTest extends TestCase
{
    // ik heb het uitgezet. ik snap het niet en het is deprecated voor een deel.

    public function testVoteOnUnsupportedAttribute()
    {
        $this->markTestSkipped();
        //        $token = $this->getMockForAbstractClass(TokenInterface::class);
        //        $token->method('getRoles')->willReturn(['CONTROLLER_APP_KLANTEN']);
        //
        //        $roleHierarchy = $this->getMockForAbstractClass(RoleHierarchyInterface::class);
        //
        //        $voter = new ControllerVoter($roleHierarchy);
        //
        //        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, new \stdClass(), ['UNSUPPORTED_ATTRIBUTE']));
    }

    public function testVoteOnReachableRole()
    {
        $this->markTestSkipped();
        //
        //        $token = $this->getMockForAbstractClass(TokenInterface::class);
        //        $token->method('getRoles')->willReturn(['CONTROLLER_APP_KLANTEN']);
        //
        //        $roleHierarchy = $this->getMockForAbstractClass(RoleHierarchyInterface);
        //        $roleHierarchy->method('getReachableRoles')->willReturn([new Role('CONTROLLER_APP_KLANTEN')]);
        //
        //        $voter = new ControllerVoter($roleHierarchy);
        //
        //        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, new \stdClass(), ['CONTROLLER_APP_KLANTEN']));
    }

    public function testVoteOnUnreachableRole()
    {
        $this->markTestSkipped();
        //
        //        $token = $this->getMockForAbstractClass(TokenInterface::class);
        //        $token->method('getRoles')->willReturn(['CONTROLLER_APP_KLANTEN']);
        //
        //        $roleHierarchy = $this->getMockForAbstractClass(RoleHierarchyInterface::class);
        //        $roleHierarchy->method('getReachableRoles')->willReturn([new Role('CONTROLLER_APP_KLANTEN')]);
        //
        //        $voter = new ControllerVoter($roleHierarchy);
        //
        //        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, new \stdClass(), ['CONTROLLER_APP_OTHER']));
    }
}
