<?php

namespace GaBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = \GaBundle\Form\RapportageType::class;
    protected $tagId = 'ga.rapportage';
}
