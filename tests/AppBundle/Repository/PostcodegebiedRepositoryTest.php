<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Postcodegebied;
use AppBundle\Repository\PostcodegebiedRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class PostcodegebiedRepositoryTest extends DoctrineTestCase
{
    public function testFindOneByPostcode()
    {
        $em = $this->getEntityManagerMock();
        $metadata = new ClassMetadata(Postcodegebied::class);

        $expectedDQL = 'SELECT postcodegebied
            FROM AppBundle\Entity\Postcodegebied postcodegebied
            WHERE :postcode BETWEEN postcodegebied.van AND postcodegebied.tot';
        $this->expectDQL($em, $expectedDQL);

        $repository = new PostcodegebiedRepository($em, $metadata);
        $repository->findOneByPostcode('1234AB');
    }
}
