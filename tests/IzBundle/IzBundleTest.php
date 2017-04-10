<?php

namespace Tests\IzBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use IzBundle\DependencyInjection\Compiler\ReportsCompilerPass;
use IzBundle\IzBundle;

class IzBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testbuild()
    {
        $container = new ContainerBuilder();

        $bundle = $this->createSUT();
        $bundle->build($container);

        $this->assertInstanceOf(
            ReportsCompilerPass::class,
            $container->getCompilerPassConfig()->getBeforeOptimizationPasses()[0]
        );
    }

    private function createSUT()
    {
        return new IzBundle();
    }
}
