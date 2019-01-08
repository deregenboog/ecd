<?php

namespace ErOpUitBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReportsCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('ErOpUitBundle\Form\RapportageType');

        $reports = [];
        foreach ($container->findTaggedServiceIds('eropuit.rapportage') as $id => $params) {
            $report = $container->getDefinition($id);
            $report->addMethodCall('setTitle', [$params[0]['title']]);
            $category = $params[0]['category'];
            $reports[$category][$id] = $report;
        }

        $definition->addArgument($reports);
    }
}
