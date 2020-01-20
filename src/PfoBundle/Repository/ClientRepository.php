<?php

namespace PfoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ClientRepository extends EntityRepository
{
    public function countByGroep(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('groep.naam AS groepnaam')
            ->innerJoin('client.groep', 'groep')
            ->innerJoin('client.verslagen', 'verslag')
            ->groupBy('groepnaam')
        ;

        $this->applyReportFilter($builder, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countByStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect("stadsdeel.naam AS stadsdeelnaam")
            ->innerJoin('client.werkgebied', 'stadsdeel')
            ->leftJoin('client.verslagen', 'verslag')
            ->groupBy('stadsdeelnaam')
        ;
//        dump($builder->getQuery()->getSQL());
        $this->applyReportFilter($builder, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function applyReportFilter(QueryBuilder $builder, \DateTime $startDate, \DateTime $endDate)
    {
        $builder
            ->where('DATE(client.created) BETWEEN :startDate AND :endDate')
            ->orWhere('DATE(verslag.created) BETWEEN :startDate AND :endDate')
            ->setParameters([
                'startDate' => $startDate,
                'endDate' => $endDate,
            ])
        ;
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('client')
            ->select('COUNT(DISTINCT client.id) AS aantal')
        ;
    }
}
