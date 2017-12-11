<?php

namespace InloopBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Klant;

class DossierStatusRepository extends EntityRepository
{
    public function findCurrentByKlantId($id)
    {
        return $this->createQueryBuilder('status')
            ->innerJoin('status.klant', 'klant', 'WITH', 'status = klant.huidigeStatus')
            ->where('klant.id = :klant_id')
            ->setParameter('klant_id', $id)
            ->getQuery()
            ->getOneOrNUllResult()
        ;
    }
}
