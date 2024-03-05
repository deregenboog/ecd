<?php

namespace Tests\DagbestedingBundle\Report;

use DagbestedingBundle\Report\DeelnemersZonderToestemmingsformulier;
use DagbestedingBundle\Service\DeelnemerDaoInterface;
use PHPUnit\Framework\TestCase;

class DeelnemersZonderToestemmingsformulierTest extends TestCase
{
    public function testReport()
    {
        $startDate = new \DateTime('2024-02-03');
        $endDate = new \DateTime('2024-04-05');

        $dagdeelDao = $this->createMock(DeelnemerDaoInterface::class);
        $dagdeelDao->expects($this->once())
            ->method('deelnemersZonderToestemmingsformulier')
            ->with(DeelnemerDaoInterface::FASE_GESTART, $startDate, $endDate)
            ->willReturn([
                [
                    'id' => 12,
                    'naam' => 'Jan',
                    'projectNaam' => 'A',
                ],
                [
                    'id' => 23,
                    'naam' => 'Piet',
                    'projectNaam' => 'B',
                ],
                [
                    'id' => 34,
                    'naam' => 'Klaas',
                    'projectNaam' => 'B',
                ],
            ])
        ;

        $report = new DeelnemersZonderToestemmingsformulier($dagdeelDao);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [
            [
                'title' => 'Deelnemers zonder VOG',
                'data' => [
                    1 => [
                        'Deelnemer nummer' => 12,
                        'Naam' => 'Jan',
                        'Project(en)' => 'A',
                    ],
                    2 => [
                        'Deelnemer nummer' => 23,
                        'Naam' => 'Piet',
                        'Project(en)' => 'B',
                    ],
                    3 => [
                        'Deelnemer nummer' => 34,
                        'Naam' => 'Klaas',
                        'Project(en)' => 'B',
                    ],
                ],
            ],
        ];
        $this->assertSame($expected, $report->getReports());
    }
}
