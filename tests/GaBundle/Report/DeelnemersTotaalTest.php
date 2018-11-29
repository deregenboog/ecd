<?php

namespace Tests\GaBundle\Report;

use GaBundle\Entity\GroepBuurtmaatjes;
use GaBundle\Report\DeelnemersTotaal;
use GaBundle\Repository\GroepRepository;

class DeelnemersTotaalTest extends \PHPUnit_Framework_TestCase
{
    private $startDate;

    private $endDate;

    public function testReportConstruction()
    {
        $report = $this->createSUT();

        $expected = [[
            'title' => '',
            'data' => [[
                'Aantal activiteiten' => 55,
                'Aantal deelnemers' => 433,
                'Aantal deelnames' => 43,
                'Aantal anonieme deelnames' => 10,
            ]]],
        ];

        $this->assertEquals($expected, $report->getReports());
    }

    private function createSUT()
    {
        $this->startDate = new \DateTime('2016-01-01');
        $this->endDate = new \DateTime('2016-12-31');

        $repository = $this->createMock(GroepRepository::class);
        $repository->method('countDeelnemers')->willReturn([
            'aantal_activiteiten' => 55,
            'aantal_deelnemers' => 433,
            'aantal_deelnames' => 43,
            'aantal_anonieme_deelnames' => 10,
        ]);

        $report = new DeelnemersTotaal([$repository]);
        $report->setStartDate($this->startDate)->setEndDate($this->endDate);

        return $report;
    }
}
