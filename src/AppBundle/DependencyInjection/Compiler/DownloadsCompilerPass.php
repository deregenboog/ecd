<?php

namespace AppBundle\DependencyInjection\Compiler;

class DownloadsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = 'AppBundle\Service\DownloadsDao';
    protected $tagId = 'app.downloads';
}
