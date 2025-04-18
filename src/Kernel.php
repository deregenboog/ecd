<?php

namespace App;

use AppBundle\DependencyInjection\Compiler\LdapCompilerPass;
use AppBundle\DependencyInjection\Compiler\ReportsCompilerPass;
use GaBundle\DependencyInjection\Compiler\GroepTypeCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected $reports = [
        \AppBundle\Form\RapportageType::class => 'app.rapportage',
        \ClipBundle\Form\RapportageType::class => 'clip.rapportage',
        \DagbestedingBundle\Form\RapportageType::class => 'dagbesteding.rapportage',
        \GaBundle\Form\RapportageType::class => 'ga.rapportage',
        \HsBundle\Form\RapportageType::class => 'hs.rapportage',
        \InloopBundle\Form\RapportageType::class => 'inloop.rapportage',
        \IzBundle\Form\RapportageType::class => 'iz.rapportage',
        \MwBundle\Form\RapportageType::class => 'mw.rapportage',
        \OekBundle\Form\RapportageType::class => 'oek.rapportage',
        \OekraineBundle\Form\RapportageType::class => 'oekraine.rapportage',
        \PfoBundle\Form\RapportageType::class => 'pfo.rapportage',
        \TwBundle\Form\RapportageType::class => 'tw.rapportage',
        \VillaBundle\Form\RapportageType::class => 'villa.rapportage',
    ];

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new LdapCompilerPass());
        $container->addCompilerPass(new GroepTypeCompilerPass());

        $this->addReportsCompilerPasses($container);
    }

    protected function addReportsCompilerPasses(ContainerBuilder $container)
    {
        foreach ($this->reports as $serviceId => $tagId) {
            $container->addCompilerPass(new ReportsCompilerPass($serviceId, $tagId));
        }
    }
}
