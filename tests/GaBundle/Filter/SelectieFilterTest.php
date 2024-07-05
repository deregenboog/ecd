<?php

declare(strict_types=1);

namespace Tests\GaBundle\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Klantdossier;
use GaBundle\Entity\Vrijwilligerdossier;
use GaBundle\Filter\SelectieFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class SelectieFilterTest extends DoctrineTestCase
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
                function (): SelectieFilter {
                    $filter = new SelectieFilter();

                    return $filter;
                },
                'SELECT FROM GaBundle\Entity\Klantdossier dossier
                    INNER JOIN dossier.klant base
                    WHERE lidmaatschap.id IS NOT NULL
                    AND DATE(lidmaatschap.startdatum) <= :today
                    AND (lidmaatschap.einddatum IS NULL OR DATE(lidmaatschap.einddatum) > :today)
                    GROUP BY dossier.id',
            ],
            [
                function (): SelectieFilter {
                    $filter = new SelectieFilter();
                    $filter->groepen;

                    return $filter;
                },
                'SELECT FROM GaBundle\Entity\Klantdossier dossier
                    INNER JOIN dossier.klant base
                    WHERE lidmaatschap.id IS NOT NULL
                    AND DATE(lidmaatschap.startdatum) <= :today
                    AND (lidmaatschap.einddatum IS NULL OR DATE(lidmaatschap.einddatum) > :today)
                    GROUP BY dossier.id',
            ],
            [
                function (): SelectieFilter {
                    $filter = new SelectieFilter();
                    $filter->stadsdelen;

                    return $filter;
                },
                'SELECT FROM GaBundle\Entity\Klantdossier dossier
                    INNER JOIN dossier.klant base
                    WHERE lidmaatschap.id IS NOT NULL
                    AND DATE(lidmaatschap.startdatum) <= :today
                    AND (lidmaatschap.einddatum IS NULL OR DATE(lidmaatschap.einddatum) > :today)
                    GROUP BY dossier.id',
            ],
            [
                function (): SelectieFilter {
                    $filter = new SelectieFilter();
                    $filter->personen = [new Klantdossier(), new Vrijwilligerdossier()];

                    return $filter;
                },
                'SELECT FROM GaBundle\Entity\Klantdossier dossier
                    INNER JOIN dossier.klant base
                    WHERE lidmaatschap.id IS NOT NULL
                    AND DATE(lidmaatschap.startdatum) <= :today
                    AND (lidmaatschap.einddatum IS NULL OR DATE(lidmaatschap.einddatum) > :today)
                    GROUP BY dossier.id',
            ],
            [
                function (): SelectieFilter {
                    $filter = new SelectieFilter();
                    $filter->communicatie = ['post'];

                    return $filter;
                },
                'SELECT FROM GaBundle\Entity\Klantdossier dossier
                    INNER JOIN dossier.klant base
                    WHERE lidmaatschap.id IS NOT NULL
                    AND DATE(lidmaatschap.startdatum) <= :today
                    AND (lidmaatschap.einddatum IS NULL OR DATE(lidmaatschap.einddatum) > :today)
                    AND (base.geenPost = false OR base.geenPost IS NULL)
                    GROUP BY dossier.id',
            ],
            [
                function (): SelectieFilter {
                    $filter = new SelectieFilter();
                    $filter->communicatie = ['email'];

                    return $filter;
                },
                'SELECT FROM GaBundle\Entity\Klantdossier dossier
                    INNER JOIN dossier.klant base
                    WHERE lidmaatschap.id IS NOT NULL
                    AND DATE(lidmaatschap.startdatum) <= :today
                    AND (lidmaatschap.einddatum IS NULL OR DATE(lidmaatschap.einddatum) > :today)
                    AND base.email IS NOT NULL
                    AND (base.geenEmail = false OR base.geenEmail IS NULL)
                    GROUP BY dossier.id',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return (new QueryBuilder($em))->from(Klantdossier::class, 'dossier');
    }
}
