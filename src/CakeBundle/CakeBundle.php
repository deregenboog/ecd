<?php

namespace CakeBundle;

use CakeBundle\DependencyInjection\Compiler\CakeConfigurationCompilerPass;
use CakeBundle\DependencyInjection\Compiler\EmailCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CakeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EmailCompilerPass());
        $container->addCompilerPass(new CakeConfigurationCompilerPass());
    }
}
