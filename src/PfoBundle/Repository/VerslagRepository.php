<?php

namespace PfoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class VerslagRepository extends EntityRepository
{
    public function countByGroepAndContacttype(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('groep.naam AS groepnaam')
            ->addSelect('verslag.type AS contacttype')
            ->innerJoin('verslag.clienten', 'client')
            ->innerJoin('client.groep', 'groep')
            ->groupBy('groepnaam', 'contacttype')
        ;

        $this->applyReportFilter($builder, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function applyReportFilter(QueryBuilder $builder, \DateTime $startDate, \DateTime $endDate)
    {
        $builder
            ->where('DATE(verslag.created) BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
        ;
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('verslag')
//             ->select('COUNT(DISTINCT verslag.id) AS aantal')
            // @todo moet een verslag met meerdere personen één of meer keren meetellen?
            // in oude PFO-module is gekozen voor meer, dus hier in eerste instantie ook
            ->select('COUNT(verslag.id) AS aantal')
        ;
    }
}
