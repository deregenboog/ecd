<?php

namespace ErOpUitBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'ErOpUitBundle\Form\RapportageType';
    protected $tagId = 'eropuit.rapportage';
}
