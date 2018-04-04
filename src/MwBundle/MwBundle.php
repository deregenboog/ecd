<?php

namespace MwBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use MwBundle\DependencyInjection\Compiler\ReportsCompilerPass;

class MwBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ReportsCompilerPass());
    }
}
