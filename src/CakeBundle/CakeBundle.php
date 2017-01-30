<?php

namespace CakeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use CakeBundle\DependencyInjection\Compiler\EmailCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CakeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EmailCompilerPass());
    }
}
