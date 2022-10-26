<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;
use Symfony\Component\HttpKernel\Kernel;


class AppKernel extends Kernel
{
    use \Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;

    public function registerBundles()
    {
        $env = $this->getEnvironment();
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
//            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
//            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
//            new JMS\AopBundle\JMSAopBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Shivas\VersioningBundle\ShivasVersioningBundle(),
            new FOS\CKEditorBundle\FOSCKEditorBundle(),
            new Symfony\WebpackEncoreBundle\WebpackEncoreBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),

            new LegacyBundle\LegacyBundle(),
            new AppBundle\AppBundle(),
            new BuurtboerderijBundle\BuurtboerderijBundle(),
            new ClipBundle\ClipBundle(),
            new DagbestedingBundle\DagbestedingBundle(),
            new ErOpUitBundle\ErOpUitBundle(),
            new GaBundle\GaBundle(),
            new HsBundle\HsBundle(),
            new InloopBundle\InloopBundle(),
            new OekraineBundle\OekraineBundle(),
            new IzBundle\IzBundle(),
            new MwBundle\MwBundle(),
            new TwBundle\TwBundle(),
            new OekBundle\OekBundle(),
            new PfoBundle\PfoBundle(),
            new ScipBundle\ScipBundle(),
            new UhkBundle\UhkBundle(),
            new VillaBundle\VillaBundle(),
        ];

        if ('test' !== $env) {
            $bundles[] = new LdapTools\Bundle\LdapToolsBundle\LdapToolsBundle();
        }

        if (in_array($env, ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
//            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();

            $bundles[] = new Nelmio\Alice\Bridge\Symfony\NelmioAliceBundle();
            $bundles[] = new Fidry\AliceDataFixtures\Bridge\Symfony\FidryAliceDataFixturesBundle();

            $bundles[] = new Hautelook\AliceBundle\HautelookAliceBundle();
            if ($env === 'test') {
                $bundles[] = new Liip\TestFixturesBundle\LiipTestFixturesBundle();
                $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
//                $bundles[] = new DAMA\DoctrineTestBundle\DAMADoctrineTestBundle();
            }
        }

        return $bundles;
    }

    public function getCacheDir()
    {
        return $this->getProjectDir().'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return $this->getProjectDir().'/var/logs/'.$this->getEnvironment();
    }


    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir().'/app/config/config_'.$this->getEnvironment().'.yml');

        if ('test' !== $this->getEnvironment()) {
            $dotenv = new Dotenv();
            try {
                $dotenv->load($this->getProjectDir().'/app/config/.env');
            } catch (PathException $e) {
                $dotenv->load($this->getProjectDir().'/app/config/.env.dist');
            }

            $profile = getenv('PROFILE');
            $loader->load($this->getProjectDir().'/app/config/roles_'.$profile.'.yml');
        }
    }

//    protected function build(Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder)
//    {
//        $containerBuilder->registerForAutoconfiguration(\AppBundle\Repository\DoelstellingRepositoryInterface::class)
//            ->addTag('app.doelstelling')
//        ;
//        parent::build($containerBuilder);
//    }
    protected function configureRoutes(\Symfony\Component\Routing\RouteCollectionBuilder $routes)
    {
        // TODO: Implement configureRoutes() method.
    }

    protected function configureContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container, LoaderInterface $loader)
    {
        $this->registerContainerConfiguration($loader);
    }
}
