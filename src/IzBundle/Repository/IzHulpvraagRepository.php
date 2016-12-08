<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class IzHulpvraagRepository extends EntityRepository
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
            ->addSelect('klant.werkgebied AS stadsdeel')
            ->groupBy('klant.werkgebied')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('izHulpvraag')
            ->select('COUNT(izHulpvraag.id) AS aantal')
            ->innerJoin('izHulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
            ->innerJoin('izHulpvraag.izHulpaanbod', 'izHulpaanbod')
            ->innerJoin('izHulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('izHulpvraag.izEindeKoppeling', 'izEindeKoppelingHulpvraag')
            ->leftJoin('izHulpaanbod.izEindeKoppeling', 'izEindeKoppelingHulpaanbod')
            ->leftJoin('izKlant.izAfsluiting', 'izAfsluitingKlant')
            ->leftJoin('izVrijwilliger.izAfsluiting', 'izAfsluitingVrijwilliger')
            ->andWhere('izAfsluitingKlant.id IS NULL OR izAfsluitingKlant.naam <> :foutieve_invoer')
            ->andWhere('izAfsluitingVrijwilliger.id IS NULL OR izAfsluitingVrijwilliger.naam <> :foutieve_invoer')
            ->andWhere('izEindeKoppelingHulpvraag.id IS NULL OR izEindeKoppelingHulpvraag.naam <> :foutieve_invoer')
            ->andWhere('izEindeKoppelingHulpaanbod.id IS NULL OR izEindeKoppelingHulpaanbod.naam <> :foutieve_invoer')
            ->setParameter('foutieve_invoer', 'Foutieve invoer')
        ;
    }

    private function applyReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        switch($report) {
            case 'beginstand':
                $builder
                ->andWhere('izHulpvraag.koppelingStartdatum < :startdatum')
                ->andWhere($builder->expr()->orX(
                    'izHulpvraag.koppelingEinddatum IS NULL',
                    "izHulpvraag.koppelingEinddatum = '0000-00-00'",
                    'izHulpvraag.koppelingEinddatum >= :startdatum'
                ))
                ->setParameter('startdatum', $startDate)
                ;
                break;
            case 'gestart':
                $builder
                ->andWhere('izHulpvraag.koppelingStartdatum >= :startdatum')
                ->andWhere('izHulpvraag.koppelingStartdatum <= :einddatum')
                ->setParameter('startdatum', $startDate)
                ->setParameter('einddatum', $endDate)
                ;
                break;
            case 'afgesloten':
                $builder
                ->andWhere('izHulpvraag.koppelingEinddatum >= :startdatum')
                ->andWhere('izHulpvraag.koppelingEinddatum <= :einddatum')
                ->setParameter('startdatum', $startDate)
                ->setParameter('einddatum', $endDate)
                ;
                break;
            case 'succesvol_afgesloten':
                $builder
                ->andWhere('izHulpvraag.koppelingEinddatum >= :startdatum')
                ->andWhere('izHulpvraag.koppelingEinddatum <= :einddatum')
                ->andWhere('izHulpvraag.koppelingSuccesvol = true')
                ->setParameter('startdatum', $startDate)
                ->setParameter('einddatum', $endDate)
                ;
                break;
            case 'eindstand':
                $builder
                ->andWhere('izHulpvraag.koppelingStartdatum <= :einddatum')
                ->andWhere($builder->expr()->orX(
                    'izHulpvraag.koppelingEinddatum IS NULL',
                    "izHulpvraag.koppelingEinddatum = '0000-00-00'",
                    'izHulpvraag.koppelingEinddatum > :einddatum'
                ))
                ->setParameter('einddatum', $endDate)
                ;
                break;
        }
    }
}
