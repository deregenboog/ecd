<?php

namespace GaBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'ga.form.rapportage';
    protected $tagId = 'ga.rapportage';
}
