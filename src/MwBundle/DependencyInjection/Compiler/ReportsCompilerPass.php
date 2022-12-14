<?php

namespace MwBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = \MwBundle\Form\RapportageType::class;
    protected $tagId = 'mw.rapportage';
}
