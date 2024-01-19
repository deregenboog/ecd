<?php

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\Service\DownloadsDao;

class DownloadsCompilerPass extends AbstractReportsCompilerPass
{
    protected $serviceId = DownloadsDao::class;
    protected $tagId = 'app.downloads';
}
