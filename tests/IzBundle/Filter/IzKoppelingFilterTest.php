<?php

namespace Tests\IzBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\VrijwilligerFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;
use IzBundle\Filter\KoppelingFilter;
use PHPUnit\Framework\TestCase;

class IzKoppelingFilterTest extends TestCase
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
        $filter->koppelingStartdatum = new AppDateRangeModel(new \DateTime('2016-01-01'));
        $filter->applyTo($builder);

        $this->assertEquals(
            'hulpvraag.koppelingStartdatum >= :koppelingStartdatum_van AND (hulpvraag.koppelingEinddatum IS NULL OR hulpvraag.koppelingEinddatum > :now)',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->koppelingStartdatum->getStart(),
            $builder->getParameter('koppelingStartdatum_van')->getValue()
        );
    }

    public function testKoppelingEinddatumFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $filter->koppelingEinddatum = new AppDateRangeModel(null, new \DateTime('2016-12-31'));
        $filter->applyTo($builder);

        $this->assertEquals(
            'hulpvraag.koppelingEinddatum <= :koppelingEinddatum_tot AND (hulpvraag.koppelingEinddatum IS NULL OR hulpvraag.koppelingEinddatum > :now)',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->koppelingEinddatum->getEnd(),
            $builder->getParameter('koppelingEinddatum_tot')->getValue()
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
            'hulpvraag.koppelingEinddatum IS NULL OR hulpvraag.koppelingEinddatum > :now',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $now->getTimestamp(),
            $builder->getParameter('now')->getValue()->getTimestamp()
        );
    }

    public function testProjectFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $now = new \DateTime('now');
        $filter->project = new Project();
        $filter->applyTo($builder);

        $this->assertEquals(
            '(hulpvraag.koppelingEinddatum IS NULL OR hulpvraag.koppelingEinddatum > :now) AND hulpvraag.project = :project',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->project,
            $builder->getParameter('project')->getValue()
        );
    }

    public function testHulpvraagFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $now = new \DateTime('now');
        $filter->hulpvraagMedewerker = new Medewerker();
        $filter->applyTo($builder);

        $this->assertEquals(
            '(hulpvraag.koppelingEinddatum IS NULL OR hulpvraag.koppelingEinddatum > :now) AND hulpvraag.medewerker = :hulpvraagMedewerker',
            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->hulpvraagMedewerker,
            $builder->getParameter('hulpvraagMedewerker')->getValue()
        );
    }

    public function testHulpaanbodFilter()
    {
        $builder = $this->createQueryBuilder();

        $filter = $this->createSUT();
        $now = new \DateTime('now');
        $filter->hulpvraagMedewerker = new Medewerker();
        $filter->applyTo($builder);

        $this->assertEquals(
            '(hulpvraag.koppelingEinddatum IS NULL OR hulpvraag.koppelingEinddatum > :now) AND hulpvraag.medewerker = :hulpvraagMedewerker',

            (string) $builder->getDQLPart('where')
        );
        $this->assertEquals(
            $filter->hulpvraagMedewerker,
            $builder->getParameter('hulpvraagMedewerker')->getValue()
        );
    }

    private function createSUT()
    {
        return new KoppelingFilter();
    }

    private function createQueryBuilder()
    {
        $emStub = $this->createMock(EntityManager::class);

        return new QueryBuilder($emStub);
    }
}
