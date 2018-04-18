<?php

namespace AppBundle\Security;

class RolesMapper
{
    private $map = [];

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function defineConstants()
    {
        foreach ($this->map as $roleName => $ldapGroup) {
            if (!defined($roleName)) {
                define($roleName, $ldapGroup);
            }
        }

        return $this;
    }

    public function getRoles(array $ldapGroups = [])
    {
        // convert AD-groups to roles
        $roles = str_replace(array_values($this->map), array_keys($this->map), $ldapGroups);

        foreach ($roles as $i => $role) {
            if (false === strpos($role, 'ROLE_')) {
                unset($roles[$i]);
            }
        }

        $roles = array_values(array_unique($roles));

        return $roles;
    }
}
