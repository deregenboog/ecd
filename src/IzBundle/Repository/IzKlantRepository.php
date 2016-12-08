<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class IzKlantRepository extends EntityRepository
{
    public function count($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder();
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case 'gestart':
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()->select('izKlant.id');
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $beginstandBuilder->getQuery()->getResult();
                $builder->andWhere('izKlant.id NOT IN (:beginstand)')->setParameter('beginstand', $beginstand);
                break;
            case 'afgesloten':
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()->select('izKlant.id');
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $eindstand = $eindstandBuilder->getQuery()->getResult();
                $builder->andWhere('izKlant.id NOT IN (:eindstand)')->setParameter('eindstand', $eindstand);
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByProject($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('izProject.naam AS project')
            ->innerJoin('izHulpvraag.izProject', 'izProject')
            ->groupBy('izProject')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case 'gestart':
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, izProject.id)")
                    ->innerJoin('izHulpvraag.izProject', 'izProject')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $beginstandBuilder->getQuery()->getResult();
                array_walk($beginstand, function (&$item) {
                    $item = current($item);
                });
                $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, izProject.id)", $beginstand));
                break;
            case 'afgesloten':
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, izProject.id)")
                    ->innerJoin('izHulpvraag.izProject', 'izProject')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $eindstand = $eindstandBuilder->getQuery()->getResult();
                // flatten array
                array_walk($eindstand, function (&$item) {
                    $item = current($item);
                });
                $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, izProject.id)", $eindstand));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('klant.werkgebied AS stadsdeel')
            ->groupBy('klant.werkgebied')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case 'gestart':
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, klant.werkgebied)")
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $beginstandBuilder->getQuery()->getResult();
                array_walk($beginstand, function (&$item) {
                    $item = current($item);
                });
                $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, klant.werkgebied)", $beginstand));
                break;
            case 'afgesloten':
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, klant.werkgebied)")
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $eindstand = $eindstandBuilder->getQuery()->getResult();
                // flatten array
                array_walk($eindstand, function (&$item) {
                    $item = current($item);
                });
                $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, klant.werkgebied)", $eindstand));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('izKlant')
            ->select('COUNT(DISTINCT izKlant.id) AS aantal')
            ->innerJoin('izKlant.klant', 'klant')
            ->innerJoin('izKlant.izHulpvragen', 'izHulpvraag')
            ->innerJoin('izHulpvraag.izHulpaanbod', 'izHulpaanbod')
            ->innerJoin('izHulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('izKlant.izAfsluiting', 'izAfsluiting')
            ->andWhere('izAfsluiting.id IS NULL OR izAfsluiting.naam <> :foutieve_invoer')
            ->setParameter('foutieve_invoer', 'Foutieve invoer')
        ;
    }

    private function applyReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        switch ($report) {
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
