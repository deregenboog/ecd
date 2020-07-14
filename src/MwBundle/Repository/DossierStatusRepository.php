<?php

namespace MwBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DossierStatusRepository extends EntityRepository
{
    public function findCurrentByKlantId($id)
    {
        return $this->createQueryBuilder('mwstatus')
            ->innerJoin('mwstatus.klant', 'klant', 'WITH', 'mwstatus = klant.huidigeMwStatus')
            ->where('klant.id = :klant_id')
            ->setParameter('klant_id', $id)
            ->getQuery()
            ->getOneOrNUllResult()
        ;
    }
}
