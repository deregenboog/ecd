<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use CakeBundle\Service\CakeConfiguration;

class AppKernel extends Kernel
{
    public function boot()
    {
        parent::boot();

        if ('cli' === php_sapi_name()) {
            @define('WWW_ROOT', '');
        }

        // configure CakeHPHP
        require __DIR__.'/bootstrap_cake.php';
        /** @var $cakeConfig CakeConfiguration */
        $cakeConfig = $this->getContainer()->get('cake.configuration');
        // define constants for acl groups
        foreach ($cakeConfig->all()['ACL.groups'] as $name => $id) {
            if (!defined($name)) {
                define($name, $id);
            }
        }
        // set CakePHP's Configure-object
        foreach ($cakeConfig->all() as $key => $value) {
            \Configure::write($key, $value);
        }
    }

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Shivas\VersioningBundle\ShivasVersioningBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new AppBundle\AppBundle(),
            new CakeBundle\CakeBundle(),
            new ClipBundle\ClipBundle(),
            new DagbestedingBundle\DagbestedingBundle(),
            new GaBundle\GaBundle(),
            new HsBundle\HsBundle(),
            new InloopBundle\InloopBundle(),
            new IzBundle\IzBundle(),
            new MwBundle\MwBundle(),
            new OdpBundle\OdpBundle(),
            new OekBundle\OekBundle(),
            new PfoBundle\PfoBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Hautelook\AliceBundle\HautelookAliceBundle();
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }

        return $bundles;
    }

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
    }
}
