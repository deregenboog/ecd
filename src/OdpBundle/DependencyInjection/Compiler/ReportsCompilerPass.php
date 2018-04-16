<?php

namespace OdpBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'odp.form.rapportage';

    protected $tagId = 'odp.rapportage';
}
