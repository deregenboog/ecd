<?php

namespace GaBundle\DependencyInjection\Compiler;

use GaBundle\Service\GroupTypeContainer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GroepTypeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(GroupTypeContainer::class);
        foreach ($container->findTaggedServiceIds('ga.groep_type') as $id => $tags) {
            $definition->addMethodCall('setType', [$tags[0]['title'], new Reference($id)]);
        }
    }
}
