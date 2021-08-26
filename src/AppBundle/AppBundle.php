<?php

namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\DoelstellingenCompilerPass;
use AppBundle\DependencyInjection\Compiler\DownloadsCompilerPass;
use AppBundle\DependencyInjection\Compiler\ReportsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ReportsCompilerPass());
        $container->addCompilerPass(new DoelstellingenCompilerPass());
        $container->addCompilerPass(new DownloadsCompilerPass());
    }
}
