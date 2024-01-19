<?php

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\Exception\AppException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ReportsCompilerPass implements CompilerPassInterface
{
    protected $serviceId;

    protected $tagId;

    public function __construct($serviceId, $tagId)
    {
        $this->serviceId = $serviceId;
        $this->tagId = $tagId;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
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

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getTagId(): string
    {
        return $this->tagId;
    }
}
