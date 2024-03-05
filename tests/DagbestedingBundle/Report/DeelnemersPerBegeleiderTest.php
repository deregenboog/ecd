<?php

namespace Tests\DagbestedingBundle\Report;

use DagbestedingBundle\Report\DeelnemersPerBegeleider;
use DagbestedingBundle\Service\DeelnemerDaoInterface;
use PHPUnit\Framework\TestCase;

class DeelnemersPerBegeleiderTest extends TestCase
{
    public function testReport()
    {
        $startDate = new \DateTime('2024-02-03');
        $endDate = new \DateTime('2024-04-05');

        $dagdeelDao = $this->createMock(DeelnemerDaoInterface::class);
        $dagdeelDao->expects($this->exactly(4))
            ->method('countByBegeleider')
            ->withConsecutive(
                [DeelnemerDaoInterface::FASE_BEGINSTAND, $startDate, $endDate],
                [DeelnemerDaoInterface::FASE_GESTART, $startDate, $endDate],
                [DeelnemerDaoInterface::FASE_GESTOPT, $startDate, $endDate],
                [DeelnemerDaoInterface::FASE_EINDSTAND, $startDate, $endDate],
            )
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

        $report = new DeelnemersPerBegeleider($dagdeelDao);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [
            [
                'title' => 'Beginstand',
                'xDescription' => '',
                'yDescription' => 'Begeleider',
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
            [
                'title' => 'Gestart',
                'xDescription' => '',
                'yDescription' => 'Begeleider',
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
            [
                'title' => 'Gestopt',
                'xDescription' => '',
                'yDescription' => 'Begeleider',
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
            [
                'title' => 'Eindstand',
                'xDescription' => '',
                'yDescription' => 'Begeleider',
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
