<?php

namespace AppBundle\DependencyInjection\Compiler;
use AppBundle\Form\RapportageType;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = RapportageType::class;
    protected $tagId = 'app.rapportage';
}
