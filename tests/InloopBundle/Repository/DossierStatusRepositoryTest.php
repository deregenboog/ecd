<?php

namespace Tests\InloopBundle\Repository;

use Doctrine\ORM\Mapping\ClassMetadata;
use InloopBundle\Entity\DossierStatus;
use InloopBundle\Repository\DossierStatusRepository;
use Tests\AppBundle\Repository\RepositoryTestCase;

class DossierStatusRepositoryTest extends RepositoryTestCase
{
    public function testFindCurrentByKlantId()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(DossierStatus::class);

        $expectedDQL = 'SELECT status
            FROM InloopBundle\\Entity\\DossierStatus status
            INNER JOIN status.klant klant WITH status = klant.huidigeStatus
            WHERE klant.id = :klant_id';
        $this->expectDQL($em, $expectedDQL);

        $repository = new DossierStatusRepository($em, $metadata);
        $repository->findCurrentByKlantId(123);
    }
}
