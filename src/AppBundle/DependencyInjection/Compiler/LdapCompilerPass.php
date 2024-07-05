<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LdapCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('security.user.provider.ldap');
        $definition->setClass('AppBundle\Security\LdapUserProvider');
        $definition->addMethodCall('setRolesGroups', ['$rolesGroups' => '%roles_groups%']);
    }
}
