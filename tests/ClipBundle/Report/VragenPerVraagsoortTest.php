<?php

namespace Tests\ClipBundle\Report;

use ClipBundle\Report\VragenPerVraagsoort;
use ClipBundle\Service\ContactmomentDaoInterface;
use ClipBundle\Service\VraagDaoInterface;
use PHPUnit\Framework\TestCase;

class VragenPerVraagsoortTest extends TestCase
{
    public function testReport()
    {
        $startDate = new \DateTime('2024-01-01');
        $endDate = new \DateTime('2024-12-31');

        $vraagDao = $this->createMock(VraagDaoInterface::class);
        $vraagDao->expects($this->once())
            ->method('countByVraagsoort')
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
            ->method('countByVraagsoort')
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

        $report = new VragenPerVraagsoort($vraagDao, $contactmomentenDao);
        $report->setStartDate($startDate)->setEndDate($endDate);

        $expected = [
            [
                'title' => '',
                'xDescription' => '',
                'yDescription' => 'Onderwerp',
                'data' => [
                    'E-mail' => [
                        'Contactmomenten' => 212,
                        'Vragen' => 12,
                        'Totaal' => 224,
                    ],
                    'Telefoon' => [
                        'Contactmomenten' => 121,
                        'Vragen' => 21,
                        'Totaal' => 142,
                    ],
                    'Totaal' => [
                        'Contactmomenten' => 333,
                        'Vragen' => 33,
                        'Totaal' => 366,
                    ],
                ],
            ],
        ];
        $this->assertSame($expected, $report->getReports());
    }
}
