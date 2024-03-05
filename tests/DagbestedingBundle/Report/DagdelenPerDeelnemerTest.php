<?php

namespace Tests\DagbestedingBundle\Report;

use DagbestedingBundle\Report\DagdelenPerDeelnemer;
use DagbestedingBundle\Service\DagdeelDaoInterface;
use PHPUnit\Framework\TestCase;

class DagdelenPerDeelnemerTest extends TestCase
{
    public function testReport()
    {
        $startDate = new \DateTime('2024-02-03');
        $endDate = new \DateTime('2024-04-05');

        $dagdeelDao = $this->createMock(DagdeelDaoInterface::class);
        $dagdeelDao->expects($this->exactly(3))
            ->method('countByDeelnemer')
            ->withConsecutive(
                [new \DateTime('2024-02-01'), new \DateTime('2024-02-29')],
                [new \DateTime('2024-03-01'), new \DateTime('2024-03-31')],
                [new \DateTime('2024-04-01'), new \DateTime('2024-04-30')],
            )
            ->willReturn([
                [
                    'naam' => 'Jan Pietersen',
                    'aanwezigheid' => 'A',
                    'aantal' => 12,
                ],
                [
                    'naam' => 'Jan Pietersen',
                    'aanwezigheid' => 'Z',
                    'aantal' => 23,
                ],
                [
                    'naam' => 'Jan Pietersen',
                    'aanwezigheid' => 'O',
                    'aantal' => 34,
                ],
                [
                    'naam' => 'Piet Jansen',
                    'aanwezigheid' => 'V',
                    'aantal' => 45,
                ],
                [
                    'naam' => 'Piet Jansen',
                    'aanwezigheid' => 'A',
                    'aantal' => 56,
                ],
            ])
        ;

        $report = new DagdelenPerDeelnemer($dagdeelDao);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [
            [
                'title' => '02-2024',
                'xDescription' => 'Aanwezigheid',
                'yDescription' => 'Deelnemer',
                'data' => [
                    'Jan Pietersen' => [
                        'A' => 12,
                        'O' => 34,
                        'V' => 0,
                        'Z' => 23,
                        'Totaal' => 69,
                    ],
                    'Piet Jansen' => [
                        'A' => 56,
                        'O' => 0,
                        'V' => 45,
                        'Z' => 0,
                        'Totaal' => 101,
                    ],
                    'Totaal' => [
                        'A' => 68,
                        'O' => 34,
                        'V' => 45,
                        'Z' => 23,
                        'Totaal' => 170,
                    ],
                ],
            ],
            [
                'title' => '03-2024',
                'xDescription' => 'Aanwezigheid',
                'yDescription' => 'Deelnemer',
                'data' => [
                    'Jan Pietersen' => [
                        'A' => 12,
                        'O' => 34,
                        'V' => 0,
                        'Z' => 23,
                        'Totaal' => 69,
                    ],
                    'Piet Jansen' => [
                        'A' => 56,
                        'O' => 0,
                        'V' => 45,
                        'Z' => 0,
                        'Totaal' => 101,
                    ],
                    'Totaal' => [
                        'A' => 68,
                        'O' => 34,
                        'V' => 45,
                        'Z' => 23,
                        'Totaal' => 170,
                    ],
                ],
            ],
            [
                'title' => '04-2024',
                'xDescription' => 'Aanwezigheid',
                'yDescription' => 'Deelnemer',
                'data' => [
                    'Jan Pietersen' => [
                        'A' => 12,
                        'O' => 34,
                        'V' => 0,
                        'Z' => 23,
                        'Totaal' => 69,
                    ],
                    'Piet Jansen' => [
                        'A' => 56,
                        'O' => 0,
                        'V' => 45,
                        'Z' => 0,
                        'Totaal' => 101,
                    ],
                    'Totaal' => [
                        'A' => 68,
                        'O' => 34,
                        'V' => 45,
                        'Z' => 23,
                        'Totaal' => 170,
                    ],
                ],
            ],
        ];
        $this->assertSame($expected, $report->getReports());
    }
}
