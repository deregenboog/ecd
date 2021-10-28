<?php

namespace TwBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'tw.form.rapportage';

    protected $tagId = 'tw.rapportage';
}
