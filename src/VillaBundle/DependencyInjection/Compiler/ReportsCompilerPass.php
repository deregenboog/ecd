<?php

namespace VillaBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'villa.form.rapportage';
    protected $tagId = 'villa.rapportage';
}
