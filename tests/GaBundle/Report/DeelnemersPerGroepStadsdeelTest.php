<?php

declare(strict_types=1);

namespace Tests\GaBundle\Report;

use Doctrine\ORM\EntityRepository;
use GaBundle\Repository\GroepRepository;
use Tests\GaBundle\MockObjects\GroepARepository;
use Tests\GaBundle\MockObjects\GroepBRepository;
use GaBundle\Report\DeelnemersPerGroepStadsdeel;
use PHPUnit\Framework\TestCase;

class DeelnemersPerGroepStadsdeelTest extends TestCase
{
    public function testGetReports()
    {
        $startDate = new \DateTime('2016-01-01');
        $endDate = new \DateTime('2016-12-31');

        // Mock the 'EntityRepository' and 'getRepository' method
        $entityRepoMock = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRepository'])  // specify the methods to be mocked
            ->getMock();

        $entityRepoMock->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback(function ($className) {
                if ($className == 'GroepA') return new GroepARepository();
                if ($className == 'GroepB') return new GroepBRepository();
            });

        $repositoryA = $entityRepoMock->getRepository("GroepA");
        $repositoryB = $entityRepoMock->getRepository("GroepB");

        /**
         * Bovenstaande code even laten staan om te bespreken met Bart.
         * Wat ikg edaan heb:
         * de test faalde. Dat kwam omdat ik de impementatie van de $repositories
         * anders heb gedaan, nl via de servicce container en !tagged_iterator.
         * daardoor kon er geen k=>v meer meegegeven worden met $group
         * dus dat moest obv de reflectie van de entityClassname
         * omdat de repository geen class is maar met object een factory gemaakt obv entity...
         * Lang verhaal kort:
         * ik heb met de AI assistent geprobeert het te snappen en dat werkt wel aardig
         * want ik dacht we moeten die entityRepository mocken en dan daar de factory aanroepen
         * en die moet iets returnen.
         * Daar heb ik vervolgens mock objects voor gemaakt. So far so good.
         * En dus de functie om de titel te returnen...
         *
         * Het werkte :)
         *
         * En toen ik wilde committen en de diff even bekeek en mn commit message wilde schrijven dacht ik:
         * Ja lekekr, ik kan natuurijk ook zelf die getRepositoryTitle in de mock zetten...
         * En dan is het hetzelfde als het was, met een extra toevoeging..
         *
         * Nu is mijn vraag aan jou: ik kan hem laten staan obv de mockObjcts en factory
         * maar eigenlijk is dat omslchtig voor mn gevoel. Ik heb wel wat geleerd ;) maar ben even benieuwd
         * naar jouw mening.
         *
         */


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
        $repositoryA->expects($this->any())
            ->method('getRepositoryTitle')
            ->willReturn("Groep A")
        ;

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

        $repositoryB->expects($this->any())
            ->method('getRepositoryTitle')
            ->willReturn('Groep B')
        ;


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
