<?php

namespace Tests\GaBundle\Report;

use GaBundle\Report\DeelnemersTotaal;
use GaBundle\Repository\GroepRepository;
use GaBundle\Entity\GroepBuurtmaatjes;

class DeelnemersTotaalTest extends \PHPUnit_Framework_TestCase
{
    private $startDate;

    private $endDate;

    public function testReportConstruction()
    {
        $report = $this->createSUT();

        $expected = [
            [
                'title' => '',
                'data' => [
                    'Totaal' => [
                        'Aantal activiteiten' => 55,
                        'Aantal deelnemers' => 433,
                        'Aantal unieke deelnemers' => 43,
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $report->getReports());
    }

    private function createSUT()
    {
        $this->startDate = new \DateTime('2016-01-01');
        $this->endDate = new \DateTime('2016-12-31');

        $repository = $this->createMock(GroepRepository::class);
        $repository->method('countDeelnemers')->willReturn(
            [
                [
                    0 => new GroepBuurtmaatjes(),
                    'aantal_activiteiten' => 55,
                    'aantal_deelnemers' => 433,
                    'aantal_unieke_deelnemers' => 43,
                ],
            ]
        );

        $report = new DeelnemersTotaal($repository);
        $report->setStartDate($this->startDate)->setEndDate($this->endDate);

        return $report;
    }
}
