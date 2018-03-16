<?php

namespace OekBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'oek.form.rapportage';

    protected $tagId = 'oek.rapportage';
}
