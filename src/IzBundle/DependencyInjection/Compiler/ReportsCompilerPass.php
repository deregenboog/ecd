<?php

namespace IzBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = \IzBundle\Form\RapportageType::class;
    protected $tagId = 'iz.rapportage';
}
