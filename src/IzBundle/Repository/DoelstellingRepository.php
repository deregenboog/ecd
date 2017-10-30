<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Postcodegebied;

class DoelstellingRepository extends EntityRepository
{
    public function countByJaar($year)
    {
        return $this->createQueryBuilder('doelstelling')
            ->select('p.naam as project, doelstelling.aantal')
            ->innerJoin('doelstelling.project', 'p')
            ->where('doelstelling.jaar = :jaar')
            ->setParameter('jaar', $year)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByJaarAndStadsdeel($year)
    {
        return $this->createQueryBuilder('doelstelling')
            ->select('p.naam as project, s.naam as stadsdeel, doelstelling.aantal')
            ->innerJoin('doelstelling.project', 'p')
            ->innerJoin('doelstelling.stadsdeel', 's')
            ->where('doelstelling.jaar = :jaar')
            ->groupBy('doelstelling.stadsdeel')
            ->setParameter('jaar', $year)
            ->getQuery()
            ->getResult()
        ;
    }
}
