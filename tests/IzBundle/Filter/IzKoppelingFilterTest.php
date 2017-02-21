<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\VrijwilligerFilter;
use Doctrine\ORM\EntityManager;
use IzBundle\Entity\IzHulpvraag;
use AppBundle\Entity\Klant;

class IzKoppelingFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testKlantFilter()
    {
        $builder = $this->createQueryBuilder();

        $klantFilterMock = $this->createMock(KlantFilter::class);
        $klantFilterMock->expects($this->once())->method('applyTo')->with($builder);

        $filter = $this->createSUT();
        $filter->klant = $klantFilterMock;
        $filter->applyTo($builder);
    }

    public function testVrijwilligerFilter()
    {
        $builder = $this->createQueryBuilder();

        $vrijwilligerFilterMock = $this->createMock(VrijwilligerFilter::class);
        $vrijwilligerFilterMock->expects($this->once())->method('applyTo')->with($builder);

        $filter = $this->createSUT();
        $filter->vrijwilliger = $vrijwilligerFilterMock;
        $filter->applyTo($builder);
    }

    public function testKoppelingStartdatumFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $filter->koppelingStartdatum = new \DateTime('2016-01-01');
        $filter->applyTo($builder);

        $this->assertEquals(
            'izHulpvraag.koppelingStartdatum = :koppelingStartdatum',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->koppelingStartdatum,
            $builder->getParameter('koppelingStartdatum')->getValue()
        );
    }

    public function testKoppelingEinddatumFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $filter->koppelingEinddatum = new \DateTime('2016-12-31');
        $filter->applyTo($builder);

        $this->assertEquals(
            'izHulpvraag.koppelingEinddatum = :koppelingEinddatum',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->koppelingEinddatum,
            $builder->getParameter('koppelingEinddatum')->getValue()
        );
    }

    public function testLopendeKoppelingenFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $now = new \DateTime('now');
        $filter->lopendeKoppelingen = true;
        $filter->applyTo($builder);

        $this->assertEquals(
            'izHulpvraag.koppelingEinddatum IS NULL OR izHulpvraag.koppelingEinddatum > :now',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $now,
            $builder->getParameter('now')->getValue()
        );
    }

    public function testIzProjectFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $now = new \DateTime('now');
        $filter->izProject = new IzProject();
        $filter->applyTo($builder);

        $this->assertEquals(
            'izHulpvraag.izProject = :izProject',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->izProject,
            $builder->getParameter('izProject')->getValue()
        );
    }

    public function testIzHulpvraagFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $now = new \DateTime('now');
        $filter->izHulpvraagMedewerker = new Medewerker();
        $filter->applyTo($builder);

        $this->assertEquals(
            'izHulpvraag.medewerker = :izHulpvraagMedewerker',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->izHulpvraagMedewerker,
            $builder->getParameter('izHulpvraagMedewerker')->getValue()
        );
    }

    public function testIzHulpaanbodFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $now = new \DateTime('now');
        $filter->izHulpaanbodMedewerker = new Medewerker();
        $filter->applyTo($builder);

        $this->assertEquals(
            'izHulpaanbod.medewerker = :izHulpaanbodMedewerker',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->izHulpaanbodMedewerker,
            $builder->getParameter('izHulpaanbodMedewerker')->getValue()
        );
    }

    private function createSUT()
    {
        return new IzKoppelingFilter();
    }

    private function createQueryBuilder()
    {
        $emStub = $this->createMock(EntityManager::class);

        return new QueryBuilder($emStub);
    }
}
