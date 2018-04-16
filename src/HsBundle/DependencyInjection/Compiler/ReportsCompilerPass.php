<?php

namespace HsBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'hs.form.rapportage';
    protected $tagId = 'hs.rapportage';
}
