<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function getCacheDir()
    {
        return $this->getRootDir().'/../var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return $this->getRootDir().'/../var/logs/'.$this->getEnvironment();
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');

        if ('test' !== $this->getEnvironment()) {
            $dotenv = new Dotenv();
            try {
                $dotenv->load($this->getRootDir().'/config/.env');
            } catch (PathException $e) {
                $dotenv->load($this->getRootDir().'/config/.env.dist');
            }

            $profile = getenv('PROFILE');
            $loader->load($this->getRootDir().'/config/roles_'.$profile.'.yml');
        }
    }
}
