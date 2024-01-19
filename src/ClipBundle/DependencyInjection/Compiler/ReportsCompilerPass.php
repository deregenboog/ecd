<?php

namespace ClipBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;
use ClipBundle\Form\RapportageType;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = RapportageType::class;
    protected $tagId = 'clip.rapportage';
}
