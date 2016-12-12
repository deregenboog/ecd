<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class IzVrijwilligerRepository extends EntityRepository
{
    public function count($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder();
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countByProject($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('izProject.naam AS project')
            ->innerJoin('izHulpaanbod.izProject', 'izProject')
            ->groupBy('izProject')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countByStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('vrijwilliger.werkgebied AS stadsdeel')
            ->groupBy('vrijwilliger.werkgebied')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('izVrijwilliger')
            ->select('COUNT(DISTINCT izVrijwilliger.id) AS aantal')
            ->innerJoin('izVrijwilliger.izIntake', 'izIntake')
            ->innerJoin('izVrijwilliger.izHulpaanbiedingen', 'izHulpaanbod')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('izVrijwilliger.izAfsluiting', 'izAfsluiting')
            ->andWhere('izAfsluiting.id IS NULL OR izAfsluiting.naam <> :foutieve_invoer')
            ->setParameter('foutieve_invoer', 'Foutieve invoer')
        ;
    }

    private function applyReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        switch ($report) {
            case 'beginstand':
                $builder
                    ->andWhere($builder->expr()->orX(
                        'izIntake.intakeDatum IS NULL',
                        'izIntake.intakeDatum < :startdatum'
                    ))
                    ->andWhere($builder->expr()->orX(
                        'izVrijwilliger.afsluitDatum IS NULL',
                        "izVrijwilliger.afsluitDatum = '0000-00-00'",
                        'izVrijwilliger.afsluitDatum >= :startdatum'
                    ))
                    ->setParameter('startdatum', $startDate)
                ;
                break;
            case 'gestart':
                $builder
                    ->andWhere('izIntake.intakeDatum >= :startdatum')
                    ->andWhere('izIntake.intakeDatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            case 'afgesloten':
                $builder
                    ->andWhere('izVrijwilliger.afsluitDatum >= :startdatum')
                    ->andWhere('izVrijwilliger.afsluitDatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            case 'eindstand':
                $builder
                    ->andWhere($builder->expr()->orX(
                        'izIntake.intakeDatum IS NULL',
                        'izIntake.intakeDatum <= :einddatum'
                    ))
                    ->andWhere($builder->expr()->orX(
                        'izVrijwilliger.afsluitDatum IS NULL',
                        "izVrijwilliger.afsluitDatum = '0000-00-00'",
                        'izVrijwilliger.afsluitDatum > :einddatum'
                    ))
                    ->setParameter('einddatum', $endDate)
                ;
                break;
        }
    }
}
