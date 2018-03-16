<?php

namespace ClipBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\AbstractReportsCompilerPass;

class ReportsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'clip.form.rapportage';
    protected $tagId = 'clip.rapportage';
}
