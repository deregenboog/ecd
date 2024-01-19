<?php

namespace ErOpUitBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;
use ErOpUitBundle\Form\RapportageType;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = RapportageType::class;
    protected $tagId = 'eropuit.rapportage';
}
