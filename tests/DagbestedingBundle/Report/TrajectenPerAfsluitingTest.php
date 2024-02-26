<?php

namespace Tests\DagbestedingBundle\Report;

use DagbestedingBundle\Report\TrajectenPerAfsluiting;
use DagbestedingBundle\Service\TrajectDaoInterface;
use PHPUnit\Framework\TestCase;

class TrajectenPerAfsluitingTest extends TestCase
{
    public function testReport()
    {
        $startDate = new \DateTime('2024-02-03');
        $endDate = new \DateTime('2024-04-05');

        $trajectDao = $this->createMock(TrajectDaoInterface::class);
        $trajectDao->expects($this->once())
            ->method('countByAfsluiting')
            ->with(TrajectDaoInterface::FASE_GESTOPT, $startDate, $endDate)
            ->willReturn([
                [
                    'aantal' => 12,
                    'groep' => 'A',
                ],
                [
                    'aantal' => 23,
                    'groep' => 'B',
                ],
                [
                    'aantal' => 34,
                    'groep' => 'C',
                ],
                [
                    'aantal' => 45,
                    'groep' => 'D',
                ],
            ])
        ;

        $report = new TrajectenPerAfsluiting($trajectDao);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [
            [
                'title' => 'Gestopt',
                'xDescription' => '',
                'yDescription' => 'Resultaat',
                'data' => [
                    'A' => [
                        'Totaal' => 12,
                    ],
                    'B' => [
                        'Totaal' => 23,
                    ],
                    'C' => [
                        'Totaal' => 34,
                    ],
                    'D' => [
                        'Totaal' => 45,
                    ],
                    'Totaal' => [
                        'Totaal' => 114,
                    ],
                ],
            ],
        ];
        $this->assertSame($expected, $report->getReports());
    }
}
