<?php

namespace InloopBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'InloopBundle\Form\RapportageType';
    protected $tagId = 'inloop.rapportage';
}
