<?php

namespace Tests\AppBundle\Filter;

use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class KlantFilterTest extends \PHPUnit_Framework_TestCase
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
            "CONCAT_WS(' ', klant.voornaam, klant.roepnaam, klant.tussenvoegsel, klant.achternaam) LIKE :klant_naam_part_0",
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
            "CONCAT_WS(' ', klant.voornaam, klant.roepnaam, klant.tussenvoegsel, klant.achternaam) LIKE :klant_naam_part_0 AND CONCAT_WS(' ', klant.voornaam, klant.roepnaam, klant.tussenvoegsel, klant.achternaam) LIKE :klant_naam_part_1",
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

        $filter = $this->createSUT();
        $filter->stadsdeel = 'Centrum';
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
        $emStub = $this->createMock(EntityManager::class);

        return new QueryBuilder($emStub);
    }
}
