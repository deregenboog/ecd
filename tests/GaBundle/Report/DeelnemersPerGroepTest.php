<?php

declare(strict_types=1);

namespace Tests\GaBundle\Report;

use GaBundle\Report\DeelnemersPerGroep;
use GaBundle\Repository\GroepRepository;
use PHPUnit\Framework\TestCase;

class DeelnemersPerGroepTest extends TestCase
{
    public function testGetReports()
    {
        $startDate = new \DateTime('2016-01-01');
        $endDate = new \DateTime('2016-12-31');

        $repositoryA = $this->createMock(GroepRepository::class);
        $repositoryA->expects($this->once())
            ->method('countDeelnemersPerGroep')
            ->with($startDate, $endDate)
            ->willReturn([
                [
                    'groepnaam' => 'Groep A',
                    'aantal_activiteiten' => 55,
                    'aantal_deelnemers' => 433,
                    'aantal_deelnames' => 43,
                    'aantal_anonieme_deelnames' => 10,
                ],
                [
                    'groepnaam' => 'Groep B',
                    'aantal_activiteiten' => 33,
                    'aantal_deelnemers' => 211,
                    'aantal_deelnames' => 21,
                    'aantal_anonieme_deelnames' => 0,
                ],
            ]);
        $repositoryB = $this->createMock(GroepRepository::class);
        $repositoryB->expects($this->once())
            ->method('countDeelnemersPerGroep')
            ->with($startDate, $endDate)
            ->willReturn([
                [
                    'groepnaam' => 'Groep C',
                    'aantal_activiteiten' => 44,
                    'aantal_deelnemers' => 322,
                    'aantal_deelnames' => 32,
                    'aantal_anonieme_deelnames' => 1,
                ],
            ]);

        $report = new DeelnemersPerGroep([
            'A' => $repositoryA,
            'B' => $repositoryB,
        ]);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [[
            'title' => 'A',
            'yDescription' => 'Groep',
            'data' => [
                'Groep A' => [
                    'Aantal activiteiten' => 55,
                    'Aantal deelnemers' => 433,
                    'Aantal deelnames' => 43,
                    'Aantal anonieme deelnames' => 10,
                ],
                'Groep B' => [
                    'Aantal activiteiten' => 33,
                    'Aantal deelnemers' => 211,
                    'Aantal deelnames' => 21,
                    'Aantal anonieme deelnames' => 0,
                ],
                'Totaal' => [
                    'Aantal activiteiten' => 88,
                    'Aantal deelnemers' => 644,
                    'Aantal deelnames' => 64,
                    'Aantal anonieme deelnames' => 10,
                ],
            ],
        ],
        [
            'title' => 'B',
            'yDescription' => 'Groep',
            'data' => [
                'Groep C' => [
                    'Aantal activiteiten' => 44,
                    'Aantal deelnemers' => 322,
                    'Aantal deelnames' => 32,
                    'Aantal anonieme deelnames' => 1,
                ],
                'Totaal' => [
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
