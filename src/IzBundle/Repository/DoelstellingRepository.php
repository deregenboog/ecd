<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;

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

    public function countByJaarWithoutStadsdeel($year)
    {
        return $this->createQueryBuilder('doelstelling')
            ->select('p.naam as project, SUM(doelstelling.aantal) as aantal')
            ->innerJoin('doelstelling.project', 'p')
            ->where('doelstelling.jaar = :jaar')
            ->andWhere('doelstelling.stadsdeel IS NULL')
            ->groupBy('doelstelling.project')
            ->setParameter('jaar', $year)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByJaarAndProjectAndStadsdeel($year)
    {
        return $this->createQueryBuilder('doelstelling')
            ->select('p.naam as project, s.naam as stadsdeel, doelstelling.aantal')
            ->innerJoin('doelstelling.project', 'p')
            ->innerJoin('doelstelling.stadsdeel', 's')
            ->where('doelstelling.jaar = :jaar')
            ->groupBy('doelstelling.project, doelstelling.stadsdeel')
            ->setParameter('jaar', $year)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByJaarAndProjectAndCategorie($year)
    {
        return $this->createQueryBuilder('doelstelling')
            ->select('p.naam as project, doelstelling.categorie, doelstelling.aantal')
            ->innerJoin('doelstelling.project', 'p')
            ->where('doelstelling.jaar = :jaar')
            ->groupBy('doelstelling.project, doelstelling.categorie')
            ->setParameter('jaar', $year)
            ->getQuery()
            ->getResult()
        ;
    }
}
