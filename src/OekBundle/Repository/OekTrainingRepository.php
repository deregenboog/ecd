<?php

namespace OekBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class OekTrainingRepository extends EntityRepository
{
    public function countByGroepAndStadsdeel(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('oekGroep.naam AS groep')
            ->addSelect('oekTraining.locatie AS stadsdeel')
            ->innerJoin('oekTraining.oekGroep', 'oekGroep')
            ->groupBy('oekGroep', 'oekTraining.locatie');

        $this->applyReportFilter($builder, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function applyReportFilter(QueryBuilder $builder, \DateTime $startDate, \DateTime $endDate)
    {
        $builder
            ->where('oekTraining.startdatum BETWEEN :startDate AND :endDate')
            ->orWhere('oekTraining.einddatum BETWEEN :startDate AND :endDate')
            ->orWhere($builder->expr()->andX(
                'oekTraining.startdatum <= :endDate',
                    'oekTraining.einddatum IS NULL'
            ))
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('oekTraining')
            ->select('COUNT(DISTINCT oekTraining.id) AS aantal')
            ;
    }
}
