<?php

namespace Tests\InloopBundle\Repository;

use AppBundle\Entity\Geslacht;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\DossierStatus;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Schorsing;
use InloopBundle\Repository\SchorsingRepository;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class SchorsingRepositoryTest extends DoctrineTestCase
{
    public function testFilterByLocatie()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Schorsing::class);

        $repository = new SchorsingRepository($em, $metadata);
        $builder = $repository->createQueryBuilder('schorsing');
        $repository->filterByLocatie($builder, new Locatie());

        $expected = 'SELECT schorsing
            FROM InloopBundle\Entity\Schorsing schorsing
            INNER JOIN schorsing.locaties locatie WITH locatie = :locatie';
        $this->assertEqualsIgnoringWhitespace($expected, $builder->getDQL());
    }

    public function testFilterByGeslacht()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Schorsing::class);

        $repository = new SchorsingRepository($em, $metadata);
        $builder = $repository->createQueryBuilder('schorsing');
        $repository->filterByGeslacht($builder, new Geslacht('man', 'M'));

        $expected = 'SELECT schorsing
            FROM InloopBundle\Entity\Schorsing schorsing
            INNER JOIN schorsing.klant klant WITH klant.geslacht = :geslacht';
        $this->assertEqualsIgnoringWhitespace($expected, $builder->getDQL());
    }

    /**
     * @dataProvider dateRangeProvider
     */
    public function testFilterByDateRange(AppDateRangeModel $dateRange, string $expectedDQL)
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Schorsing::class);

        $repository = new SchorsingRepository($em, $metadata);
        $builder = $repository->createQueryBuilder('schorsing');
        $repository->filterByDateRange($builder, $dateRange);

        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }

    public function dateRangeProvider()
    {
        return [
            [
                new AppDateRangeModel(),
                'SELECT schorsing
                    FROM InloopBundle\Entity\Schorsing schorsing',
            ],
            [
                new AppDateRangeModel(new \DateTime()),
                'SELECT schorsing
                    FROM InloopBundle\Entity\Schorsing schorsing
                    WHERE schorsing.datumVan >= :start_date',
            ],
            [
                new AppDateRangeModel(null, new \DateTime()),
                'SELECT schorsing
                    FROM InloopBundle\Entity\Schorsing schorsing
                    WHERE schorsing.datumVan <= :end_date',
            ],
            [
                new AppDateRangeModel(new \DateTime(), new \DateTime()),
                'SELECT schorsing
                    FROM InloopBundle\Entity\Schorsing schorsing
                    WHERE schorsing.datumVan >= :start_date
                    AND schorsing.datumVan <= :end_date',
            ],
        ];
    }
}
