<?php

declare(strict_types=1);

namespace Tests\GaBundle\Report;

use GaBundle\Report\DeelnemersTotaal;
use GaBundle\Repository\GroepRepository;
use GaBundle\Service\GroupTypeContainer;
use PHPUnit\Framework\TestCase;

class DeelnemersTotaalTest extends TestCase
{
    public function testGetReports()
    {
        $startDate = new \DateTime('2016-01-01');
        $endDate = new \DateTime('2016-12-31');

        $repositoryA = $this->createMock(GroepRepository::class);
        $repositoryA->method('countDeelnemers')->willReturn([
            'aantal_activiteiten' => 55,
            'aantal_deelnemers' => 433,
            'aantal_deelnames' => 43,
            'aantal_anonieme_deelnames' => 10,
        ]);
        $repositoryB = $this->createMock(GroepRepository::class);
        $repositoryB->method('countDeelnemers')->willReturn([
            'aantal_activiteiten' => 44,
            'aantal_deelnemers' => 322,
            'aantal_deelnames' => 32,
            'aantal_anonieme_deelnames' => 9,
        ]);

        $types = new GroupTypeContainer();
        $types->setType('Groep A', $repositoryA);
        $types->setType('Groep B', $repositoryB);

        $report = new DeelnemersTotaal($types);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [[
            'title' => '',
            'data' => [
                'Groep A' => [
                    'Aantal activiteiten' => 55,
                    'Aantal deelnemers' => 433,
                    'Aantal deelnames' => 43,
                    'Aantal anonieme deelnames' => 10,
                ],
                'Groep B' => [
                    'Aantal activiteiten' => 44,
                    'Aantal deelnemers' => 322,
                    'Aantal deelnames' => 32,
                    'Aantal anonieme deelnames' => 9,
                ],
            ],
        ]];

        $this->assertSame($expected, $report->getReports());
    }
}
