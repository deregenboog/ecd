<?php

namespace Tests\HsBundle\Repository;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\Mapping\ClassMetadata;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Repository\FactuurRepository;
use Tests\AppBundle\Repository\RepositoryTestCase;

class FactuurRepositoryTest extends RepositoryTestCase
{
    public function testFindNonLockedByKlantAndDateRange()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Factuur::class);

        $expectedDQL = "SELECT factuur, registratie, declaratie
            FROM HsBundle\Entity\Factuur factuur
            LEFT JOIN factuur.registraties registratie
            LEFT JOIN factuur.declaraties declaratie
            WHERE factuur.locked = false
            AND factuur.klant = :klant
            AND (factuur.datum BETWEEN :start AND :end)";
        $this->expectDQL($em, $expectedDQL);

        $repository = new FactuurRepository($em, $metadata);
        $repository->findNonLockedByKlantAndDateRange(new Klant, new AppDateRangeModel(new \DateTime(), new \DateTime()));
    }

    public function testFindByKlantAndDateRange()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Factuur::class);

        $expectedDQL = "SELECT factuur
            FROM HsBundle\Entity\Factuur factuur
            WHERE factuur.klant = :klant
            AND (factuur.datum BETWEEN :start AND :end)";
        $this->expectDQL($em, $expectedDQL);

        $repository = new FactuurRepository($em, $metadata);
        $repository->findByKlantAndDateRange(new Klant, new AppDateRangeModel(new \DateTime(), new \DateTime()));
    }
}
