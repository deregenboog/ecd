<?php

namespace OdpBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use OdpBundle\DependencyInjection\Compiler\ReportsCompilerPass;

class OdpBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ReportsCompilerPass());
    }
}
