<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use AppBundle\Exception\AppException;

abstract class AbstractReportsCompilerPass implements CompilerPassInterface
{
    protected $serviceId;

    protected $tagId;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$this->serviceId) {
            throw new AppException(__CLASS__.'::serviceId must be set');
        }

        if (!$this->tagId) {
            throw new AppException(__CLASS__.'::tagId must be set');
        }

        $definition = $container->getDefinition($this->serviceId);

        $reports = [];
        foreach ($container->findTaggedServiceIds($this->tagId) as $id => $params) {
            if (array_key_exists('category', $params[0])) {
                $category = $params[0]['category'];
                $reports[$category][$id] = new Reference($id);
            } else {
                $reports[$id] = new Reference($id);
            }
        }

        $definition->addArgument($reports);
    }
}
