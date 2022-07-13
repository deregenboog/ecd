<?php

namespace OekraineBundle;

use OekraineBundle\DependencyInjection\Compiler\ReportsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OekraineBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ReportsCompilerPass());
    }
}
