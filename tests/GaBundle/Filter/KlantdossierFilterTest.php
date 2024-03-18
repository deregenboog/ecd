<?php

declare(strict_types=1);

namespace Tests\GaBundle\Filter;

use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Activiteit;
use GaBundle\Entity\GroepErOpUit;
use GaBundle\Filter\KlantdossierFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class KlantdossierFilterTest extends DoctrineTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testApplyTo(\Closure $filter, string $expectedDQL)
    {
        $builder = $this->createQueryBuilder();
        $filter()->applyTo($builder);
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }

    public function dataProvider()
    {
        return [
            [
                function (): KlantdossierFilter {
                    $filter = new KlantdossierFilter();

                    return $filter;
                },
                'SELECT WHERE dossier.aanmelddatum <= :today AND (dossier.afsluitdatum > :today OR dossier.afsluitdatum IS NULL)',
            ],
            [
                function (): KlantdossierFilter {
                    $filter = new KlantdossierFilter();
                    $filter->klant = $this->createMock(KlantFilter::class);
                    $filter->klant->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT WHERE dossier.aanmelddatum <= :today AND (dossier.afsluitdatum > :today OR dossier.afsluitdatum IS NULL)',
            ],
            [
                function (): KlantdossierFilter {
                    $filter = new KlantdossierFilter();
                    $filter->groep = new GroepErOpUit();

                    return $filter;
                },
                'SELECT WHERE groep = :groep AND dossier.aanmelddatum <= :today AND (dossier.afsluitdatum > :today OR dossier.afsluitdatum IS NULL)',
            ],
            [
                function (): KlantdossierFilter {
                    $filter = new KlantdossierFilter();
                    $filter->activiteit = new Activiteit();

                    return $filter;
                },
                'SELECT WHERE activiteit= :activiteit AND dossier.aanmelddatum <= :today AND (dossier.afsluitdatum > :today OR dossier.afsluitdatum IS NULL)',
            ],
            [
                function (): KlantdossierFilter {
                    $filter = new KlantdossierFilter();
                    $filter->aanmelddatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE dossier.aanmelddatum >= :aanmelddatum_van AND dossier.aanmelddatum <= :aanmelddatum_tot AND dossier.aanmelddatum <= :today AND (dossier.afsluitdatum > :today OR dossier.afsluitdatum IS NULL)',
            ],
            [
                function (): KlantdossierFilter {
                    $filter = new KlantdossierFilter();
                    $filter->afsluitdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE dossier.afsluitdatum >= :afsluitdatum_van AND dossier.afsluitdatum <= :afsluitdatum_tot AND dossier.aanmelddatum <= :today AND (dossier.afsluitdatum > :today OR dossier.afsluitdatum IS NULL)',
            ],
            [
                function (): KlantdossierFilter {
                    $filter = new KlantdossierFilter();
                    $filter->actief = false;

                    return $filter;
                },
                'SELECT',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
