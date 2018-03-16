<?php

namespace IzBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'IzBundle\Form\RapportageType';
    protected $tagId = 'iz.rapportage';
}
