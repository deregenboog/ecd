<?php

namespace AppBundle\DependencyInjection\Compiler;

class PrestatiesCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'app.form.prestatie';
    protected $tagId = 'app.prestatie';
}
