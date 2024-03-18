<?php

declare(strict_types=1);

namespace Tests\HsBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Activiteit;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Klus;
use HsBundle\Filter\RegistratieFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class RegistratieFilterTest extends DoctrineTestCase
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
                function (): RegistratieFilter {
                    $filter = new RegistratieFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): RegistratieFilter {
                    $filter = new RegistratieFilter();
                    $filter->klus = new Klus();

                    return $filter;
                },
                'SELECT WHERE registratie.klus = :klus',
            ],
            [
                function (): RegistratieFilter {
                    $filter = new RegistratieFilter();
                    $filter->arbeider = new Dienstverlener();

                    return $filter;
                },
                'SELECT WHERE registratie.arbeider = :arbeider',
            ],
            [
                function (): RegistratieFilter {
                    $filter = new RegistratieFilter();
                    $filter->klant = $this->createMock(RegistratieFilter::class);
                    $filter->klant->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): RegistratieFilter {
                    $filter = new RegistratieFilter();
                    $filter->activiteit = new Activiteit();

                    return $filter;
                },
                'SELECT WHERE registratie.activiteit = :activiteit',
            ],
            [
                function (): RegistratieFilter {
                    $filter = new RegistratieFilter();
                    $filter->datum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE registratie.datum >= :datum_van
                    AND registratie.datum <= :datum_tot',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
