<?php

namespace PfoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ReportsCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('pfo.form.rapportage');

        $reports = [];
        foreach ($container->findTaggedServiceIds('pfo.rapportage') as $id => $params) {
            $category = $params[0]['category'];
            $reports[$category][$id] = new Reference($id);
        }

        $definition->addArgument($reports);
    }
}
