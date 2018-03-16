<?php

namespace PfoBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'pfo.form.rapportage';

    protected $tagId = 'pfo.rapportage';
}
