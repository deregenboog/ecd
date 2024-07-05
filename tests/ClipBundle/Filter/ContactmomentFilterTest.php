<?php

declare(strict_types=1);

namespace Tests\ClipBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use ClipBundle\Entity\Behandelaar;
use ClipBundle\Filter\ContactmomentFilter;
use ClipBundle\Filter\VraagFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class ContactmomentFilterTest extends DoctrineTestCase
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
                function (): ContactmomentFilter {
                    $filter = new ContactmomentFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): ContactmomentFilter {
                    $filter = new ContactmomentFilter();
                    $filter->id = 123;

                    return $filter;
                },
                'SELECT WHERE contactmoment.id = :id',
            ],
            [
                function (): ContactmomentFilter {
                    $filter = new ContactmomentFilter();
                    $filter->datum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE contactmoment.datum >= :datum_van AND contactmoment.datum <= :datum_tot',
            ],
            [
                function (): ContactmomentFilter {
                    $filter = new ContactmomentFilter();
                    $filter->behandelaar = new Behandelaar();

                    return $filter;
                },
                'SELECT WHERE contactmoment.behandelaar = :behandelaar',
            ],
            [
                function (): ContactmomentFilter {
                    $filter = new ContactmomentFilter();
                    $filter->vraag = $this->createMock(VraagFilter::class);
                    $filter->vraag->expects($this->once())->method('applyTo');

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
