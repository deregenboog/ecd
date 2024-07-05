<?php

namespace Tests\GaBundle\Repository;

use Doctrine\ORM\Mapping\ClassMetadata;
use GaBundle\Entity\Groep;
use GaBundle\Repository\GroepRepository;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class GroepRepositoryTest extends DoctrineTestCase
{
    public function testCountDeelnemers()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Groep::class);

        $expectedDQL = 'SELECT
                COUNT(DISTINCT activiteit) AS aantal_activiteiten,
                COUNT(DISTINCT deelname) AS aantal_deelnames,
                COUNT(DISTINCT klant) AS aantal_deelnemers,
                IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnames
            FROM GaBundle\Entity\Groep groep
            INNER JOIN groep.activiteiten activiteit WITH activiteit.datum BETWEEN :start AND :eind
            INNER JOIN activiteit.deelnames deelname WITH deelname.status = :aanwezig
            INNER JOIN GaBundle\Entity\Klantdossier dossier WITH deelname.dossier = dossier
            INNER JOIN dossier.klant klant';
        $this->expectDQL($em, $expectedDQL);

        $repository = new GroepRepository($em, $metadata);
        $repository->countDeelnemers(new \DateTime(), new \DateTime());
    }

    public function testCountDeelnemersPerGroep()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Groep::class);

        $expectedDQL = "SELECT
                CONCAT(groep.naam, ' (', IFNULL(werkgebied,'-'),  ')') AS groepnaam,
                COUNT(DISTINCT activiteit) AS aantal_activiteiten,
                COUNT(deelname) AS aantal_deelnames,
                COUNT(DISTINCT klant) AS aantal_deelnemers,
                (
                    SELECT SUM( a2.aantalAnoniemeDeelnemers) AS a
                    FROM GaBundle\Entity\Activiteit a2
                    WHERE a2.groep = groep AND a2.datum BETWEEN :start AND :eind
                    GROUP BY a2.groep
                ) aantal_anonieme_deelnames
            FROM GaBundle\Entity\Groep groep
            LEFT JOIN groep.werkgebied werkgebied
            LEFT JOIN groep.activiteiten activiteit WITH activiteit.datum BETWEEN :start AND :eind
            LEFT JOIN activiteit.deelnames deelname WITH deelname.status = :aanwezig
            LEFT JOIN GaBundle\Entity\Klantdossier dossier WITH deelname.dossier = dossier
            LEFT JOIN dossier.klant klant
            GROUP BY groepnaam
            ORDER BY groep.naam ASC";
        $this->expectDQL($em, $expectedDQL);

        $repository = new GroepRepository($em, $metadata);
        $repository->countDeelnemersPerGroep(new \DateTime(), new \DateTime());
    }

    public function testCountDeelnemersPerStadsdeel()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Groep::class);

        $expectedDQL = "SELECT
                werkgebied.naam AS stadsdeel,
                COUNT(DISTINCT activiteit) AS aantal_activiteiten,
                COUNT(DISTINCT deelname) AS aantal_deelnames,
                COUNT(DISTINCT klant) AS aantal_deelnemers,
                IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnames
            FROM GaBundle\Entity\Groep groep
            INNER JOIN groep.activiteiten activiteit WITH activiteit.datum BETWEEN :start AND :eind
            INNER JOIN activiteit.deelnames deelname WITH deelname.status = :aanwezig
            INNER JOIN GaBundle\Entity\Klantdossier dossier WITH deelname.dossier = dossier
            INNER JOIN dossier.klant klant
            LEFT JOIN klant.werkgebied werkgebied
            GROUP BY stadsdeel
            ORDER BY werkgebied.naam ASC";
        $this->expectDQL($em, $expectedDQL);

        $repository = new GroepRepository($em, $metadata);
        $repository->countDeelnemersPerStadsdeel(new \DateTime(), new \DateTime());
    }

    public function testCountDeelnemersPerGroepStadsdeel()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Groep::class);

        $expectedDQL = "SELECT
                CONCAT(groep.naam, ' (', werkgebiedGroep.naam, ')') AS groepnaam,
                werkgebiedKlant.naam AS stadsdeel,
                COUNT(DISTINCT activiteit) AS aantal_activiteiten,
                COUNT(DISTINCT deelname) AS aantal_deelnames,
                COUNT(DISTINCT klant) AS aantal_deelnemers,
                IFNULL(SUM(DISTINCT activiteit.aantalAnoniemeDeelnemers), 0) AS aantal_anonieme_deelnames
            FROM GaBundle\Entity\Groep groep
            INNER JOIN groep.activiteiten activiteit WITH activiteit.datum BETWEEN :start AND :eind
            INNER JOIN activiteit.deelnames deelname WITH deelname.status = :aanwezig
            INNER JOIN GaBundle\Entity\Klantdossier dossier WITH deelname.dossier = dossier
            INNER JOIN dossier.klant klant
            LEFT JOIN klant.werkgebied werkgebiedKlant
            LEFT JOIN groep.werkgebied werkgebiedGroep
            GROUP BY groepnaam, stadsdeel
            ORDER BY groep.naam, werkgebiedKlant.naam ASC";
        $this->expectDQL($em, $expectedDQL);

        $repository = new GroepRepository($em, $metadata);
        $repository->countDeelnemersPerGroepStadsdeel(new \DateTime(), new \DateTime());
    }

    public function testCountVrijwilligersPerGroep()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Groep::class);

        $expectedDQL = "SELECT
                CONCAT(groep.naam, ' (', werkgebied.naam, ')') AS groepnaam,
                COUNT(DISTINCT activiteit) AS aantal_activiteiten,
                COUNT(DISTINCT deelname) AS aantal_vrijwilligersdeelnames,
                COUNT(DISTINCT vrijwilliger) AS aantal_vrijwilligers
            FROM GaBundle\Entity\Groep groep
            LEFT JOIN groep.werkgebied werkgebied
            INNER JOIN groep.activiteiten activiteit WITH activiteit.datum BETWEEN :start AND :eind
            INNER JOIN activiteit.deelnames deelname WITH deelname.status = :aanwezig
            INNER JOIN GaBundle\Entity\Vrijwilligerdossier dossier WITH deelname.dossier = dossier
            INNER JOIN dossier.vrijwilliger vrijwilliger
            GROUP BY groepnaam
            ORDER BY groep.naam ASC";
        $this->expectDQL($em, $expectedDQL);

        $repository = new GroepRepository($em, $metadata);
        $repository->countVrijwilligersPerGroep(new \DateTime(), new \DateTime());
    }

    public function testCountVrijwilligersPerGroepStadsdeel()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Groep::class);

        $expectedDQL = "SELECT
                CONCAT(groep.naam, ' (', werkgebiedGroep.naam, ')') AS groepnaam,
                werkgebiedVrijwilliger.naam AS stadsdeel,
                COUNT(DISTINCT deelname) AS aantal_vrijwilligersdeelnames,
                COUNT(DISTINCT vrijwilliger) AS aantal_vrijwilligers
            FROM GaBundle\Entity\Groep groep
            INNER JOIN groep.activiteiten activiteit WITH activiteit.datum BETWEEN :start AND :eind
            INNER JOIN activiteit.deelnames deelname WITH deelname.status = :aanwezig
            INNER JOIN GaBundle\Entity\Vrijwilligerdossier dossier WITH deelname.dossier = dossier
            INNER JOIN dossier.vrijwilliger vrijwilliger
            LEFT JOIN vrijwilliger.werkgebied werkgebiedVrijwilliger
            LEFT JOIN groep.werkgebied werkgebiedGroep
            GROUP BY groepnaam, stadsdeel
            ORDER BY groep.naam, werkgebiedVrijwilliger.naam ASC";
        $this->expectDQL($em, $expectedDQL);

        $repository = new GroepRepository($em, $metadata);
        $repository->countVrijwilligersPerGroepStadsdeel(new \DateTime(), new \DateTime());
    }
}
