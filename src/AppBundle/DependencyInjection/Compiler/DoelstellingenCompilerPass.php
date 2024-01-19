<?php

namespace AppBundle\DependencyInjection\Compiler;
use AppBundle\Form\DoelstellingType;

class DoelstellingenCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = DoelstellingType::class;
    protected $tagId = 'app.doelstelling';
}
