<?php

namespace OekBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\DeelnameStatus;

class TrainingRepository extends EntityRepository
{
    public function countByNaamAndGroep(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('groep.naam AS groepnaam')
            ->addSelect('training.naam AS trainingnaam')
            ->innerJoin('training.groep', 'groep')
            ->innerJoin('training.deelnames', 'deelname')
            ->innerJoin('deelname.deelnemer', 'deelnemer')
            ->innerJoin('deelnemer.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->andwhere('deelname.deelnameStatus != :status_verwijderd')
            ->groupBy('groepnaam', 'trainingnaam')
            ->setParameter(':status_verwijderd', DeelnameStatus::STATUS_VERWIJDERD);

        $this->applyReportFilter($builder, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function applyReportFilter(QueryBuilder $builder, \DateTime $startDate, \DateTime $endDate)
    {
        $builder
            ->andwhere('training.startdatum BETWEEN :startDate AND :endDate')
            ->orWhere('training.einddatum BETWEEN :startDate AND :endDate')
            ->orWhere($builder->expr()->andX(
                'training.startdatum <= :endDate',
                'training.einddatum IS NULL'
            ))
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
        ;
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('training')
            ->select('COUNT(DISTINCT training.id) AS aantal')
        ;
    }
}
