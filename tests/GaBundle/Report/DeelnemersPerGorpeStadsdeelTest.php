<?php

declare(strict_types=1);

namespace Tests\GaBundle\Report;

use GaBundle\Report\DeelnemersPerGroepStadsdeel;
use GaBundle\Repository\GroepRepository;
use PHPUnit\Framework\TestCase;

class DeelnemersPerGorpeStadsdeelTest extends TestCase
{
    public function testGetReports()
    {
        $startDate = new \DateTime('2016-01-01');
        $endDate = new \DateTime('2016-12-31');

        $repositoryA = $this->createMock(GroepRepository::class);
        $repositoryA->expects($this->once())
            ->method('countDeelnemersPerGroepStadsdeel')
            ->with($startDate, $endDate)
            ->willReturn([
                [
                    'groepnaam' => 'Groep A',
                    'stadsdeel' => 'Stadsdeel A',
                    'aantal_deelnemers' => 43,
                    'aantal_deelnames' => 433,
                ],
                [
                    'groepnaam' => 'Groep A',
                    'stadsdeel' => 'Stadsdeel B',
                    'aantal_deelnemers' => 21,
                    'aantal_deelnames' => 211,
                ],
            ]);
        $repositoryB = $this->createMock(GroepRepository::class);
        $repositoryB->expects($this->once())
            ->method('countDeelnemersPerGroepStadsdeel')
            ->with($startDate, $endDate)
            ->willReturn([
                [
                    'groepnaam' => 'Groep B',
                    'stadsdeel' => 'Stadsdeel B',
                    'aantal_deelnemers' => 21,
                    'aantal_deelnames' => 211,
                ],
                [
                    'groepnaam' => 'Groep B',
                    'stadsdeel' => 'Stadsdeel C',
                    'aantal_deelnemers' => 32,
                    'aantal_deelnames' => 322,
                ],
            ]);

        $report = new DeelnemersPerGroepStadsdeel([
            'Groep A' => $repositoryA,
            'Groep B' => $repositoryB,
        ]);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [
            [
                'title' => 'Groep A - Deelnemers',
                'description' => 'Aantal deelnemers, exclusief anonieme deelnames',
                'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres deelnemer (dus niet op basis van activiteitlocatie)',
                'yDescription' => 'Stadsdeel',
                'data' => [
                    'Stadsdeel A' => [
                        'Groep A' => 43,
                        'Totaal' => 43,
                    ],
                    'Stadsdeel B' => [
                        'Groep A' => 21,
                        'Totaal' => 21,
                    ],
                ],
            ],
            [
                'title' => 'Groep A - Deelnames',
                'description' => 'Aantal deelnames, exclusief anonieme deelnames',
                'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres deelnemer (dus niet op basis van activiteitlocatie)',
                'yDescription' => 'Stadsdeel',
                'data' => [
                    'Stadsdeel A' => [
                        'Groep A' => 433,
                        'Totaal' => 433,
                    ],
                    'Stadsdeel B' => [
                        'Groep A' => 211,
                        'Totaal' => 211,
                    ],
                ],
            ],
            [
                'title' => 'Groep B - Deelnemers',
                'description' => 'Aantal deelnemers, exclusief anonieme deelnames',
                'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres deelnemer (dus niet op basis van activiteitlocatie)',
                'yDescription' => 'Stadsdeel',
                'data' => [
                    'Stadsdeel B' => [
                        'Groep B' => 21,
                        'Totaal' => 21,
                    ],
                    'Stadsdeel C' => [
                        'Groep B' => 32,
                        'Totaal' => 32,
                    ],
                ],
            ],
            [
                'title' => 'Groep B - Deelnames',
                'description' => 'Aantal deelnames, exclusief anonieme deelnames',
                'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres deelnemer (dus niet op basis van activiteitlocatie)',
                'yDescription' => 'Stadsdeel',
                'data' => [
                    'Stadsdeel B' => [
                        'Groep B' => 211,
                        'Totaal' => 211,
                    ],
                    'Stadsdeel C' => [
                        'Groep B' => 322,
                        'Totaal' => 322,
                    ],
                ],
            ],
        ];

        $this->assertSame($expected, $report->getReports());
    }
}
