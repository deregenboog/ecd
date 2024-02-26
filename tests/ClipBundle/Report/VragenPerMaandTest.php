<?php

namespace Tests\ClipBundle\Report;

use ClipBundle\Report\VragenPerMaand;
use ClipBundle\Service\ContactmomentDaoInterface;
use ClipBundle\Service\VraagDaoInterface;
use PHPUnit\Framework\TestCase;

class VragenPerMaandTest extends TestCase
{
    public function testReport()
    {
        $startDate = new \DateTime('2024-01-01');
        $endDate = new \DateTime('2024-12-31');

        $vraagDao = $this->createMock(VraagDaoInterface::class);
        $vraagDao->expects($this->once())
            ->method('countByMaand')
            ->with($startDate, $endDate)
            ->willReturn([
                [
                    'groep' => 'Telefoon',
                    'aantal' => 21,
                ],
                [
                    'groep' => 'E-mail',
                    'aantal' => 12,
                ],
            ]);

        $contactmomentenDao = $this->createMock(ContactmomentDaoInterface::class);
        $contactmomentenDao->expects($this->once())
            ->method('countByMaand')
            ->with($startDate, $endDate)
            ->willReturn([
                [
                    'groep' => 'E-mail',
                    'aantal' => 212,
                ],
                [
                    'groep' => 'Telefoon',
                    'aantal' => 121,
                ],
            ]);

        $report = new VragenPerMaand($vraagDao, $contactmomentenDao);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [
            [
                'title' => '',
                'xDescription' => '',
                'yDescription' => 'Maand',
                'data' => [
                    'Telefoon' => [
                        'Vragen' => 21,
                        'Contactmomenten' => 121,
                    ],
                    'E-mail' => [
                        'Vragen' => 12,
                        'Contactmomenten' => 212,
                    ],
                    'Totaal' => [
                        'Vragen' => 33,
                        'Contactmomenten' => 333,
                    ],
                ],
            ],
        ];
        $this->assertSame($expected, $report->getReports());
    }
}
