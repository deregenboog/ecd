<?php

namespace DagbestedingBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'dagbesteding.form.rapportage';
    protected $tagId = 'dagbesteding.rapportage';
}
