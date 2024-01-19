<?php

namespace GaBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;
use GaBundle\Form\RapportageType;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = RapportageType::class;
    protected $tagId = 'ga.rapportage';
}
