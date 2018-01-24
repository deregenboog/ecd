<?php

namespace Tests\IzBundle\Report;

use IzBundle\Report\VrijwilligersPerProject;
use IzBundle\Repository\IzVrijwilligerRepository;

class VrijwilligersPerProjectTest extends \PHPUnit_Framework_TestCase
{
    private $startDate;

    private $endDate;

    public function testReportConstruction()
    {
        $report = $this->createSUT();

        $yDescription = 'Project';

        $expected = [
            [
                'title' => 'Beginstand',
                'xDescription' => 'Aantal vrijwilligers met een lopende koppeling op startdatum.',
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
                'xDescription' => 'Aantal vrijwilligers dat binnen de periode een koppeling startte en op startdatum geen lopende koppeling had.',
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
                'title' => 'Nieuw gestart',
                'xDescription' => 'Aantal vrijwilligers dat binnen de periode voor het eerst een koppeling startte.',
                'yDescription' => $yDescription,
                'data' => [
                    'Project1' => [
                        'Totaal' => 40,
                    ],
                    'Project3' => [
                        'Totaal' => 20,
                    ],
                ],
            ],
            [
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal vrijwilligers dat binnen de periode een koppeling afsloot en op einddatum geen lopende koppeling had.',
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
                'xDescription' => 'Aantal vrijwilligers met een lopende koppeling op einddatum.',
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

        $report = new VrijwilligersPerProject($this->getRepositoryStub());
        $report->setStartDate($this->startDate)->setEndDate($this->endDate);

        return $report;
    }

    private function getRepositoryStub()
    {
        $repository = $this->createMock(IzVrijwilligerRepository::class);
        $repository->method('countByProject')
            ->withConsecutive(
                ['beginstand', $this->startDate, $this->endDate],
                ['gestart', $this->startDate, $this->endDate],
                ['nieuw_gestart', $this->startDate, $this->endDate],
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

        return $repository;
    }
}
