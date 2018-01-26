<?php

namespace ErOpUitBundle;

use ErOpUitBundle\DependencyInjection\Compiler\ReportsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ErOpUitBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ReportsCompilerPass());
    }
}
