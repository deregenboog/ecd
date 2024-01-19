<?php

namespace OekraineBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;
use OekraineBundle\Form\RapportageType;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = RapportageType::class;
    protected $tagId = 'oekraine.rapportage';
}
