<?php

namespace CakeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EmailCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $parameters = $container->getParameter('cake');
        $policy = $container->getParameter('email_policy');

        if (!key_exists('email', $parameters) || !key_exists($policy, $parameters['email'])) {
            throw new \RuntimeException("No email configuration found for policy '{$policy}'");
        }

        $parameters['email'] = $parameters['email'][$policy];
        $container->setParameter('cake', $parameters);
    }
}
