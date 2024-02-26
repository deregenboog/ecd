<?php

declare(strict_types=1);

namespace Tests\GaBundle\Report;

use GaBundle\Report\DeelnemersTotaal;
use GaBundle\Repository\GroepRepository;
use PHPUnit\Framework\TestCase;

class DeelnemersTotaalTest extends TestCase
{
    public function testGetReports()
    {
        $startDate = new \DateTime('2016-01-01');
        $endDate = new \DateTime('2016-12-31');

        $repository = $this->createMock(GroepRepository::class);
        $repository->method('countDeelnemers')->willReturn([
            'aantal_activiteiten' => 55,
            'aantal_deelnemers' => 433,
            'aantal_deelnames' => 43,
            'aantal_anonieme_deelnames' => 10,
        ]);

        $report = new DeelnemersTotaal([$repository]);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [[
            'title' => '',
            'data' => [[
                'Aantal activiteiten' => 55,
                'Aantal deelnemers' => 433,
                'Aantal deelnames' => 43,
                'Aantal anonieme deelnames' => 10,
            ]],
        ]];

        $this->assertSame($expected, $report->getReports());
    }
}
