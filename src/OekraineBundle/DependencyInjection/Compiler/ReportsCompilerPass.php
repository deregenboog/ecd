<?php

namespace OekraineBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'OekraineBundle\Form\RapportageType';
    protected $tagId = 'oekraine.rapportage';
}
