<?php

namespace Tests\IzBundle\Report;

use IzBundle\Report\VrijwilligersPerProjectStadsdeel;
use IzBundle\Repository\IzVrijwilligerRepository;
use IzBundle\Report\VrijwilligersPerProjectStadsdeelKlant;

class VrijwilligersPerProjectStadsdeelTest extends \PHPUnit_Framework_TestCase
{
    private $startDate;

    private $endDate;

    public function testReportConstruction()
    {
        $report = $this->createSUT();

        $xDescription = 'Project';
        $yDescription = 'Stadsdeel van de deelnemer';

        $expected = [
            [
                'title' => 'Beginstand',
                'xDescription' => $xDescription,
                'yDescription' => $yDescription,
                'data' => [
                    'Centrum' => [
                        'Project1' => 100,
                        'Project2' => 0,
                    ],
                    'Noord' => [
                        'Project1' => 0,
                        'Project2' => 30,
                    ],
                    'Oost' => [
                        'Project1' => 0,
                        'Project2' => 250,
                    ],
                    'Zuid' => [
                        'Project1' => 40,
                        'Project2' => 0,
                    ],
                    'Totaal' => [
                        'Project1' => 140,
                        'Project2' => 280,
                    ],
                ],
            ],
            [
                'title' => 'Gestart',
                'xDescription' => $xDescription,
                'yDescription' => $yDescription,
                'data' => [
                    'Oost' => [
                        'Project1' => 50,
                        'Project3' => 0,
                    ],
                    'West' => [
                        'Project1' => 0,
                        'Project3' => 20,
                    ],
                    'Totaal' => [
                        'Project1' => 50,
                        'Project3' => 20,
                    ],
                ],
            ],
            [
                'title' => 'Nieuw gestart',
                'xDescription' => $xDescription,
                'yDescription' => $yDescription,
                'data' => [
                    'Oost' => [
                        'Project1' => 40,
                        'Project3' => 0,
                    ],
                    'West' => [
                        'Project1' => 0,
                        'Project3' => 20,
                    ],
                    'Totaal' => [
                        'Project1' => 40,
                        'Project3' => 20,
                    ],
                ],
            ],
            [
                'title' => 'Afgesloten',
                'xDescription' => $xDescription,
                'yDescription' => $yDescription,
                'data' => [
                    'Noord' => [
                        'Project1' => 0,
                        'Project2' => 20,
                    ],
                    'Zuid' => [
                        'Project1' => 40,
                        'Project2' => 0,
                    ],
                    'Totaal' => [
                        'Project1' => 40,
                        'Project2' => 20,
                    ],
                ],
            ],
            [
                'title' => 'Eindstand',
                'xDescription' => $xDescription,
                'yDescription' => $yDescription,
                'data' => [
                    'Centrum' => [
                        'Project1' => 100,
                        'Project2' => 0,
                        'Project3' => 0,
                    ],
                    'Noord' => [
                        'Project1' => 0,
                        'Project2' => 10,
                        'Project3' => 0,
                    ],
                    'Oost' => [
                        'Project1' => 250,
                        'Project2' => 50,
                        'Project3' => 0,
                    ],
                    'West' => [
                        'Project1' => 0,
                        'Project2' => 0,
                        'Project3' => 20,
                    ],
                    'Zuid' => [
                        'Project1' => 0,
                        'Project2' => 0,
                        'Project3' => 0,
                    ],
                    'Totaal' => [
                        'Project1' => 350,
                        'Project2' => 60,
                        'Project3' => 20,
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

        $repository = $this->createMock(IzVrijwilligerRepository::class);
        $repository->method('countByProjectAndStadsdeelKlant')
            ->withConsecutive(
                ['beginstand', $this->startDate, $this->endDate],
                ['gestart', $this->startDate, $this->endDate],
                ['nieuw_gestart', $this->startDate, $this->endDate],
                ['afgesloten', $this->startDate, $this->endDate],
                ['eindstand', $this->startDate, $this->endDate]
            )
            ->willReturnOnConsecutiveCalls(
                [
                    ['projectnaam' => 'Project1', 'stadsdeel' => 'Centrum', 'aantal' => 100],
                    ['projectnaam' => 'Project1', 'stadsdeel' => 'Zuid', 'aantal' => 40],
                    ['projectnaam' => 'Project2', 'stadsdeel' => 'Oost', 'aantal' => 250],
                    ['projectnaam' => 'Project2', 'stadsdeel' => 'Noord', 'aantal' => 30],
                ],
                [
                    ['projectnaam' => 'Project1', 'stadsdeel' => 'Oost', 'aantal' => 50],
                    ['projectnaam' => 'Project3', 'stadsdeel' => 'West', 'aantal' => 20],
                ],
                [
                    ['projectnaam' => 'Project1', 'stadsdeel' => 'Oost', 'aantal' => 40],
                    ['projectnaam' => 'Project3', 'stadsdeel' => 'West', 'aantal' => 20],
                ],
                [
                    ['projectnaam' => 'Project1', 'stadsdeel' => 'Zuid', 'aantal' => 40],
                    ['projectnaam' => 'Project2', 'stadsdeel' => 'Noord', 'aantal' => 20],
                ],
                [
                    ['projectnaam' => 'Project1', 'stadsdeel' => 'Centrum', 'aantal' => 100],
                    ['projectnaam' => 'Project1', 'stadsdeel' => 'Oost', 'aantal' => 250],
                    ['projectnaam' => 'Project1', 'stadsdeel' => 'Zuid', 'aantal' => 0],
                    ['projectnaam' => 'Project2', 'stadsdeel' => 'Oost', 'aantal' => 50],
                    ['projectnaam' => 'Project2', 'stadsdeel' => 'Noord', 'aantal' => 10],
                    ['projectnaam' => 'Project3', 'stadsdeel' => 'West', 'aantal' => 20],
                ]
            )
        ;

        $report = new VrijwilligersPerProjectStadsdeelKlant($repository);
        $report->setStartDate($this->startDate)->setEndDate($this->endDate);

        return $report;
    }
}
