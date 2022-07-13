<?php

namespace OekraineBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DossierStatusRepository extends EntityRepository
{
    public function findCurrentByBezoekerId($id)
    {
        return $this->createQueryBuilder('status')
            ->innerJoin('status.bezoeker', 'bezoeker', 'WITH', 'status = bezoeker.huidigeStatus')
            ->where('bezoeker.id = :bezoeker_id')
            ->setParameter('bezoeker_id', $id)
            ->getQuery()
            ->getOneOrNUllResult()
        ;
    }
}
