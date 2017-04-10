<?php

namespace Tests\IzBundle\Report;

use IzBundle\Report\KlantenPerProject;
use IzBundle\Repository\IzKlantRepository;

class KlantenPerProjectTest extends \PHPUnit_Framework_TestCase
{
    private $startDate;

    private $endDate;

    public function testReportConstruction()
    {
        $report = $this->createSUT();

        $xDescription = 'Aantal deelnemers met koppeling (op basis van start- en einddatum van koppeling).';
        $yDescription = 'Project';

        $expected = [
            [
                'title' => 'Beginstand',
                'xDescription' => $xDescription,
                'yDescription' => $yDescription,
                'data' => [
                    'Project1' => [
                        'Totaal' => 100,
                    ],
                    'Project2' => [
                        'Totaal' => 250,
                    ],
                ],
            ],
            [
                'title' => 'Gestart',
                'xDescription' => $xDescription,
                'yDescription' => $yDescription,
                'data' => [
                    'Project1' => [
                        'Totaal' => 50,
                    ],
                    'Project3' => [
                        'Totaal' => 20,
                    ],
                ],
            ],
            [
                'title' => 'Afgesloten',
                'xDescription' => $xDescription,
                'yDescription' => $yDescription,
                'data' => [
                    'Project1' => [
                        'Totaal' => 40,
                    ],
                    'Project2' => [
                        'Totaal' => 20,
                    ],
                ],
            ],
            [
                'title' => 'Eindstand',
                'xDescription' => $xDescription,
                'yDescription' => $yDescription,
                'data' => [
                    'Project1' => [
                        'Totaal' => 110,
                    ],
                    'Project2' => [
                        'Totaal' => 230,
                    ],
                    'Project3' => [
                        'Totaal' => 20,
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

        $repository = $this->createMock(IzKlantRepository::class);
        $repository->method('countByProject')
            ->withConsecutive(
                ['beginstand', $this->startDate, $this->endDate],
                ['gestart', $this->startDate, $this->endDate],
                ['afgesloten', $this->startDate, $this->endDate],
                ['eindstand', $this->startDate, $this->endDate]
            )
            ->willReturnOnConsecutiveCalls(
                [
                    ['project' => 'Project1', 'aantal' => 100],
                    ['project' => 'Project2', 'aantal' => 250],
                ],
                [
                    ['project' => 'Project1', 'aantal' => 50],
                    ['project' => 'Project3', 'aantal' => 20],
                ],
                [
                    ['project' => 'Project1', 'aantal' => 40],
                    ['project' => 'Project2', 'aantal' => 20],
                ],
                [
                    ['project' => 'Project1', 'aantal' => 110],
                    ['project' => 'Project2', 'aantal' => 230],
                    ['project' => 'Project3', 'aantal' => 20],
                ]
            )
        ;

        $report = new KlantenPerProject($repository);
        $report->setStartDate($this->startDate)->setEndDate($this->endDate);

        return $report;
    }
}
