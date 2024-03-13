<?php

declare(strict_types=1);

namespace Tests\GaBundle\Report;

use GaBundle\Report\DeelnemersPerStadsdeel;
use GaBundle\Repository\GroepRepository;
use PHPUnit\Framework\TestCase;

class DeelnemersPerStadsdeelTest extends TestCase
{
    public function testGetReports()
    {
        $startDate = new \DateTime('2016-01-01');
        $endDate = new \DateTime('2016-12-31');

        $repositoryA = $this->createMock(GroepRepository::class);
        $repositoryA->expects($this->once())
            ->method('countDeelnemersPerStadsdeel')
            ->with($startDate, $endDate)
            ->willReturn([
                [
                    'stadsdeel' => 'Stadsdeel A',
                    'aantal_activiteiten' => 55,
                    'aantal_deelnemers' => 433,
                    'aantal_deelnames' => 43,
                    'aantal_anonieme_deelnames' => 10,
                ],
                [
                    'stadsdeel' => 'Stadsdeel B',
                    'aantal_activiteiten' => 33,
                    'aantal_deelnemers' => 211,
                    'aantal_deelnames' => 21,
                    'aantal_anonieme_deelnames' => 0,
                ],
            ]);
        $repositoryA->expects($this->any())
            ->method('getRepositoryTitle')
            ->willReturn("Groep A")
        ;
        $repositoryB = $this->createMock(GroepRepository::class);
        $repositoryB->expects($this->once())
            ->method('countDeelnemersPerStadsdeel')
            ->with($startDate, $endDate)
            ->willReturn([
                [
                    'stadsdeel' => 'Stadsdeel C',
                    'aantal_activiteiten' => 44,
                    'aantal_deelnemers' => 322,
                    'aantal_deelnames' => 32,
                    'aantal_anonieme_deelnames' => 1,
                ],
            ]);

        $repositoryB->expects($this->any())
            ->method('getRepositoryTitle')
            ->willReturn("Groep B")
        ;
        $report = new DeelnemersPerStadsdeel([
            'Groep A' => $repositoryA,
            'Groep B' => $repositoryB,
        ]);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [[
            'title' => 'Groep A',
            'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres deelnemer (dus niet op basis van activiteitlocatie)',
            'yDescription' => 'Stadsdeel',
            'data' => [
                'Stadsdeel A' => [
                    'Aantal activiteiten' => 55,
                    'Aantal deelnemers' => 433,
                    'Aantal deelnames' => 43,
                    'Aantal anonieme deelnames' => 10,
                ],
                'Stadsdeel B' => [
                    'Aantal activiteiten' => 33,
                    'Aantal deelnemers' => 211,
                    'Aantal deelnames' => 21,
                    'Aantal anonieme deelnames' => 0,
                ],
            ],
        ],
        [
            'title' => 'Groep B',
            'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres deelnemer (dus niet op basis van activiteitlocatie)',
            'yDescription' => 'Stadsdeel',
            'data' => [
                'Stadsdeel C' => [
                    'Aantal activiteiten' => 44,
                    'Aantal deelnemers' => 322,
                    'Aantal deelnames' => 32,
                    'Aantal anonieme deelnames' => 1,
                ],
            ],
        ]];

        $this->assertSame($expected, $report->getReports());
    }
}
