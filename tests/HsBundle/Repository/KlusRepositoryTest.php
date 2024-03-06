<?php

namespace Tests\HsBundle\Repository;

use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Repository\DoelstellingRepositoryInterface;
use AppBundle\Repository\DoelstellingRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;
use HsBundle\Repository\KlusRepository;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class KlusRepositoryTest extends DoctrineTestCase
{
    public function testFindNonLockedByKlantAndDateRange()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Klus::class);

        $expectedDQL = "SELECT factuur, registratie, declaratie
            FROM HsBundle\Entity\Klus factuur
            LEFT JOIN factuur.registraties registratie
            LEFT JOIN factuur.declaraties declaratie
            WHERE factuur.locked = false
            AND factuur.klant = :klant
            AND (factuur.datum BETWEEN :start AND :end)";
        $this->expectDQL($em, $expectedDQL);

        $repository = new KlusRepository($em, $metadata);
        $repository->findNonLockedByKlantAndDateRange(new Klant, new AppDateRangeModel(new \DateTime(), new \DateTime()));
    }

    public function testFindByKlantAndDateRange()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Klus::class);

        $expectedDQL = "SELECT factuur
            FROM HsBundle\Entity\Klus factuur
            WHERE factuur.klant = :klant
            AND (factuur.datum BETWEEN :start AND :end)";
        $this->expectDQL($em, $expectedDQL);

        $repository = new KlusRepository($em, $metadata);
        $repository->findByKlantAndDateRange(new Klant, new AppDateRangeModel(new \DateTime(), new \DateTime()));
    }
}
