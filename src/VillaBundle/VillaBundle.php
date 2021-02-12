<?php

namespace VillaBundle;

use ClipBundle\DependencyInjection\Compiler\ReportsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class VillaBundle extends Bundle
{
    static public $title = "Villa";

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ReportsCompilerPass());
    }

}
