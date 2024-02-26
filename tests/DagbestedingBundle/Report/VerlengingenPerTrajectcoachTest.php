<?php

namespace Tests\DagbestedingBundle\Report;

use DagbestedingBundle\Report\VerlengingenPerTrajectcoach;
use DagbestedingBundle\Service\TrajectDaoInterface;
use PHPUnit\Framework\TestCase;

class VerlengingenPerTrajectcoachTest extends TestCase
{
    public function testReport()
    {
        $startDate = new \DateTime('2024-02-03');
        $endDate = new \DateTime('2024-04-05');

        $trajectDao = $this->createMock(TrajectDaoInterface::class);
        $trajectDao->expects($this->once())
            ->method('getVerlengingenPerTrajectcoach')
            ->with($startDate, $endDate)
            ->willReturn([
                [
                    'trajectCoach' => 'Coach A',
                    'naam' => 'Jan',
                    'einddatum' => 'A',
                ],
                [
                    'trajectCoach' => 'Coach A',
                    'naam' => 'Piet',
                    'einddatum' => 'B',
                ],
                [
                    'trajectCoach' => 'Coach B',
                    'naam' => 'Klaas',
                    'einddatum' => 'B',
                ],
            ])
        ;

        $report = new VerlengingenPerTrajectcoach($trajectDao);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [
            [
                'title' => 'Verlengingen per trajectcoach',
                // 'xDescription' => '',
                // 'yDescription' => 'Resultaat',
                'data' => [
                    1 => [
                        'Trajectcoach' => 'Coach A',
                        'Naam' => 'Jan',
                        'Einddatum' => 'A',
                    ],
                    [
                        'Trajectcoach' => 'Coach A',
                        'Naam' => 'Piet',
                        'Einddatum' => 'B',
                    ],
                    [
                        'Trajectcoach' => 'Coach B',
                        'Naam' => 'Klaas',
                        'Einddatum' => 'B',
                    ],
                ],
            ],
        ];
        $this->assertSame($expected, $report->getReports());
    }
}
