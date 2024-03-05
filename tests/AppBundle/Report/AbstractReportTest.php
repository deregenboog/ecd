<?php

declare(strict_types=1);

namespace Tests\AppBundle\Report;

use AppBundle\Report\AbstractReport;
use PHPUnit\Framework\TestCase;

class AbstractReportTest extends TestCase
{
    public function testInitAndBuildReport()
    {
        $report = $this->getMockForAbstractClass(AbstractReport::class, [], '',
            false, false, true, ['init', 'build']);
        $report->expects($this->once())->method('init');
        $report->expects($this->once())->method('build');

        $report->getReports();
    }
}
