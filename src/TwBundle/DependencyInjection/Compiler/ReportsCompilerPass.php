<?php

namespace TwBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = \TwBundle\Form\RapportageType::class;
    protected $tagId = 'tw.rapportage';
}
