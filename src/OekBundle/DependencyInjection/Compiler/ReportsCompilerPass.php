<?php

namespace OekBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;
use OekBundle\Form\RapportageType;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = RapportageType::class;

    protected $tagId = 'oek.rapportage';
}
