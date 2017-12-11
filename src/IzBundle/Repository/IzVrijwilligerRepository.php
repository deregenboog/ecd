<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\MatchingKlant;

class IzVrijwilligerRepository extends EntityRepository
{
    public function findMatching(MatchingKlant $matching)
    {
        $builder = $this->createQueryBuilder('izVrijwilliger')
            ->select('izVrijwilliger, vrijwilliger')
            ->leftJoin('izVrijwilliger.matching', 'matching')
            ->innerJoin('izVrijwilliger.izHulpaanbiedingen', 'izHulpaanbod', 'WITH', 'izHulpaanbod.einddatum IS NULL AND izHulpaanbod.izHulpvraag IS NULL')
            ->innerJoin('izVrijwilliger.izIntake', 'izIntake')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->andWhere('vrijwilliger.disabled IS NULL OR vrijwilliger.disabled = 0')
            ->andWhere('izVrijwilliger.afsluitDatum IS NULL')
            ->orderBy('izHulpaanbod.startdatum', 'ASC')
        ;

        // doelgroepen
        $builder
            ->innerJoin('matching.doelgroepen', 'doelgroep', 'WITH', 'doelgroep IN (:doelgroepen)')
            ->setParameter('doelgroepen', $matching->getDoelgroepen())
        ;

        // hulpvraagsoort
        $builder
            ->innerJoin('matching.hulpvraagsoorten', 'hulpvraagsoort', 'WITH', 'hulpvraagsoort = :hulpvraagsoort')
            ->setParameter('hulpvraagsoort', $matching->getHulpvraagsoort())
        ;

        // spreek Nederlands
        if (false === $matching->isSpreektNederlands()) {
            $builder->andWhere('matching.voorkeurVoorNederlands = false');
        }

        return $builder->setMaxResults(20)->getQuery()->getResult();
    }

    public function count($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder();
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function select($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getSelectBuilder();
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

    public function selectByProject($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getSelectBuilder()
            ->addSelect('izProject.naam AS project')
            ->innerJoin('izHulpaanbod.izProject', 'izProject')
            ->addGroupBy('izProject')
            ->orderBy('izProject.naam')
            ->addOrderBy('vrijwilliger.achternaam, vrijwilliger.voornaam, vrijwilliger.tussenvoegsel')
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

    public function countByProjectAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('vrijwilliger.werkgebied AS stadsdeel')
            ->addSelect('izProject.naam AS project')
            ->innerJoin('izHulpaanbod.izProject', 'izProject')
            ->groupBy('izProject', 'vrijwilliger.werkgebied')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('izVrijwilliger')
            ->select('COUNT(DISTINCT izVrijwilliger.id) AS aantal')
            ->innerJoin('izVrijwilliger.izIntake', 'izIntake')
            ->innerJoin('izVrijwilliger.izHulpaanbiedingen', 'izHulpaanbod', 'WITH', 'izHulpaanbod.einddatum IS NULL')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('izVrijwilliger.izAfsluiting', 'izAfsluiting')
            ->andWhere('izAfsluiting.id IS NULL OR izAfsluiting.naam <> :foutieve_invoer')
            ->setParameter('foutieve_invoer', 'Foutieve invoer')
        ;
    }

    private function getSelectBuilder()
    {
        return $this->createQueryBuilder('izVrijwilliger')
            ->select('vrijwilliger.id')
            ->addSelect("CONCAT_WS(' ', vrijwilliger.voornaam, vrijwilliger.tussenvoegsel, vrijwilliger.achternaam) AS naam, COUNT(DISTINCT izHulpaanbod.id) AS hulpaanbiedingen")
            ->innerJoin('izVrijwilliger.izIntake', 'izIntake')
            ->innerJoin('izVrijwilliger.izHulpaanbiedingen', 'izHulpaanbod', 'WITH', 'izHulpaanbod.einddatum IS NULL')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('izVrijwilliger.izAfsluiting', 'izAfsluiting')
            ->andWhere('izAfsluiting.id IS NULL OR izAfsluiting.naam <> :foutieve_invoer')
            ->groupBy('izVrijwilliger.id')
            ->orderBy('vrijwilliger.achternaam, vrijwilliger.voornaam, vrijwilliger.tussenvoegsel')
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
            default:
                throw new \RuntimeException("Unknown report filter '{$report}' in class ".__CLASS__);
        }
    }
}
