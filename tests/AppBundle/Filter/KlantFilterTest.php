<?php

namespace Tests\AppBundle\Filter;

use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use PHPUnit\Framework\TestCase;

class KlantFilterTest extends TestCase
{
    public function testIdFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $filter->id = 1;
        $filter->applyTo($builder);

        $this->assertEquals(
            'klant.id = :klant_id',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->id,
            $builder->getParameter('klant_id')->getValue()
        );
    }

    public function testNaamFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $filter->naam = 'Bart';
        $filter->applyTo($builder);

        $this->assertEquals(
            'klant.voornaam LIKE :klant_naam_part_0 OR klant.roepnaam LIKE :klant_naam_part_0 OR klant.tussenvoegsel LIKE :klant_naam_part_0 OR klant.achternaam LIKE :klant_naam_part_0',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            "%{$filter->naam}%",
            $builder->getParameter('klant_naam_part_0')->getValue()
        );
    }

    public function testNaamMultiplePartsFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $filter->naam = 'Bart   Huttinga'; // multiple spaces intended
        $filter->applyTo($builder);

        $this->assertEquals(
            '(klant.voornaam LIKE :klant_naam_part_0 OR klant.roepnaam LIKE :klant_naam_part_0 OR klant.tussenvoegsel LIKE :klant_naam_part_0 OR klant.achternaam LIKE :klant_naam_part_0) AND (klant.voornaam LIKE :klant_naam_part_1 OR klant.roepnaam LIKE :klant_naam_part_1 OR klant.tussenvoegsel LIKE :klant_naam_part_1 OR klant.achternaam LIKE :klant_naam_part_1)',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            '%Bart%',
            $builder->getParameter('klant_naam_part_0')->getValue()
        );
        $this->assertEquals(
            '%Huttinga%',
            $builder->getParameter('klant_naam_part_1')->getValue()
        );
    }

    public function testGeboortedatumFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $filter->geboortedatum = new \DateTime('1981-07-15');
        $filter->applyTo($builder);

        $this->assertEquals(
            'klant.geboortedatum = :klant_geboortedatum',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->geboortedatum,
            $builder->getParameter('klant_geboortedatum')->getValue()
        );
    }

    public function testStadsdeelFilter()
    {
        $builder = $this->createQueryBuilder();

        $locatie = new Locatie();
        $locatie->setNaam('Centrum');

        $filter = $this->createSUT();
        $filter->stadsdeel = $locatie;
        $filter->applyTo($builder);

        $this->assertEquals(
            'klant.werkgebied = :klant_stadsdeel',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->stadsdeel,
            $builder->getParameter('klant_stadsdeel')->getValue()
        );
    }

    private function createSUT()
    {
        return new KlantFilter();
    }

    private function createQueryBuilder()
    {
        $emStub = $this->createConfiguredMock(EntityManager::class, [
            'getExpressionBuilder' => new Expr(),
        ]);

        return new QueryBuilder($emStub);
    }
}
