<?php

namespace AppBundle\Security;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ControllerVoterTest extends TestCase
{
    public function testVoteOnUnsupportedAttribute()
    {
        $token = new class() extends AbstractToken implements TokenInterface
        {
            public function getRoleNames(): array
            {
                return ['CONTROLLER_APP_KLANTEN'];
            }

            public function getCredentials() { }
        };

        $roleHierarchy = new class() implements RoleHierarchyInterface
        {
            public function getReachableRoleNames()
            {
                return [];
            }
        };

        $voter = new ControllerVoter($roleHierarchy);

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, new \stdClass(), ['UNSUPPORTED_ATTRIBUTE']));
    }

    public function testVoteOnReachableRole()
    {
        $token = new class() extends AbstractToken implements TokenInterface
        {
            public function getRoleNames(): array
            {
                return ['CONTROLLER_APP_KLANTEN'];
            }

            public function getCredentials() { }
        };

        $roleHierarchy = new class() implements RoleHierarchyInterface
        {
            public function getReachableRoleNames()
            {
                return ['CONTROLLER_APP_KLANTEN'];
            }
        };

        $voter = new ControllerVoter($roleHierarchy);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, new \stdClass(), ['CONTROLLER_APP_KLANTEN']));
    }

    public function testVoteOnUnreachableRole()
    {
        $token = new class() extends AbstractToken implements TokenInterface
        {
            public function getRoleNames(): array
            {
                return ['CONTROLLER_APP_KLANTEN'];
            }

            public function getCredentials() { }
        };

        $roleHierarchy = new class() implements RoleHierarchyInterface
        {
            public function getReachableRoleNames()
            {
                return ['CONTROLLER_APP_KLANTEN'];
            }
        };

        $voter = new ControllerVoter($roleHierarchy);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, new \stdClass(), ['CONTROLLER_APP_OTHER']));
    }
}
