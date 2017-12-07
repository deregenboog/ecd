<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\MatchingVrijwilliger;

class IzKlantRepository extends EntityRepository
{
    public function findMatching(MatchingVrijwilliger $matching)
    {
        $builder = $this->createQueryBuilder('izKlant')
            ->select('izKlant, klant')
            ->leftJoin('izKlant.matching', 'matching')
            ->innerJoin('izKlant.izHulpvragen', 'izHulpvraag', 'WITH', 'izHulpvraag.einddatum IS NULL AND izHulpvraag.izHulpaanbod IS NULL')
            ->innerJoin('izKlant.izIntake', 'izIntake')
            ->innerJoin('izKlant.klant', 'klant')
            ->andWhere('klant.disabled IS NULL OR klant.disabled = 0')
            ->andWhere('izKlant.afsluitDatum IS NULL')
            ->orderBy('izHulpvraag.startdatum', 'ASC')
        ;

        // doelgroepen
        $builder
            ->innerJoin('matching.doelgroepen', 'doelgroep', 'WITH', 'doelgroep IN (:doelgroepen)')
            ->setParameter('doelgroepen', $matching->getDoelgroepen())
        ;

        // hulpvraagsoort
        $builder
            ->innerJoin('matching.hulpvraagsoort', 'hulpvraagsoort', 'WITH', 'hulpvraagsoort IN (:hulpvraagsoorten)')
            ->setParameter('hulpvraagsoorten', $matching->getHulpvraagsoorten())
        ;

        // spreek Nederlands
        if (true === $matching->isVoorkeurVoorNederlands()) {
            $builder->andWhere('matching.spreektNederlands = true');
        }

        return $builder->setMaxResults(20)->getQuery()->getResult();
    }

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

    public function countByProjectAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('izProject.naam AS project')
            ->addSelect('klant.werkgebied AS stadsdeel')
            ->innerJoin('izHulpvraag.izProject', 'izProject')
            ->groupBy('izProject', 'klant.werkgebied');
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case 'gestart':
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, izProject.naam, klant.werkgebied)")
                    ->innerJoin('izHulpvraag.izProject', 'izProject')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $beginstandBuilder->getQuery()->getResult();
                array_walk($beginstand, function (&$item) {
                    $item = current($item);
                });
                $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, izProject.naam, klant.werkgebied)", $beginstand));
                break;
            case 'afgesloten':
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, izProject.naam, klant.werkgebied)")
                    ->innerJoin('izHulpvraag.izProject', 'izProject')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $eindstand = $eindstandBuilder->getQuery()->getResult();
                // flatten array
                array_walk($eindstand, function (&$item) {
                    $item = current($item);
                });
                $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, izProject.naam, klant.werkgebied)", $eindstand));
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
            default:
                throw new \RuntimeException("Unknown report filter '{$report}' in class ".__CLASS__);
        }
    }
}
