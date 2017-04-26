<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PostcodegebiedRepository extends EntityRepository
{
    public function findOneByPostcode($postcode)
    {
        return $this->createQueryBuilder('postcodegebied')
            ->where(':postcode BETWEEN postcodegebied.van AND postcodegebied.tot')
            ->setParameter('postcode', substr($postcode, 0, 4))
            ->getQuery()
            ->getSingleResult();
    }
}
