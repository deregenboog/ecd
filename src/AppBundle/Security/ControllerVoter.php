<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class ControllerVoter extends RoleHierarchyVoter
{
    public function __construct(RoleHierarchyInterface $roleHierarchy, $prefix = 'CONTROLLER_')
    {
        parent::__construct($roleHierarchy, $prefix);
    }
}
