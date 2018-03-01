<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\MatchingVrijwilliger;
use IzBundle\Entity\IzHulpvraag;

class IzKlantRepository extends EntityRepository
{
    const REPORT_BEGINSTAND = 'beginstand';
    const REPORT_GESTART = 'gestart';
    const REPORT_NIEUW_GESTART = 'nieuw_gestart';
    const REPORT_AFGESLOTEN = 'afgesloten';
    const REPORT_EINDSTAND = 'eindstand';

    public function findMatching(MatchingVrijwilliger $matching)
    {
        $builder = $this->createQueryBuilder('izKlant')
            ->select('izKlant, klant')
            ->leftJoin('izKlant.matching', 'matching')
            ->innerJoin('izKlant.izHulpvragen', 'izHulpvraag', 'WITH', 'izHulpvraag.einddatum IS NULL AND izHulpvraag.izHulpaanbod IS NULL')
            ->innerJoin('izKlant.izIntake', 'izIntake')
            ->innerJoin('izKlant.klant', 'klant')
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
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()->select('izKlant.id');
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $beginstandBuilder->getQuery()->getResult();
                $builder->andWhere('izKlant.id NOT IN (:beginstand)')->setParameter('beginstand', $beginstand);
                break;
            case self::REPORT_AFGESLOTEN:
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
            ->addSelect('project.naam AS projectnaam')
            ->innerJoin('izHulpvraag.project', 'project')
            ->groupBy('project')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.id)")
                    ->innerJoin('izHulpvraag.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izKlant.id, project.id)",
                    $this->flatten($beginstandBuilder->getQuery()->getResult())
                ));
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.id)")
                    ->innerJoin('izHulpvraag.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izKlant.id, project.id)",
                    $this->flatten($eindstandBuilder->getQuery()->getResult())
                ));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->groupBy('stadsdeel')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izKlant.id, werkgebied.naam)",
                    $this->flatten($beginstandBuilder->getQuery()->getResult())
                ));
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izKlant.id, werkgebied.naam)",
                    $this->flatten($eindstandBuilder->getQuery()->getResult())
                ));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByProjectAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('izHulpvraag.project', 'project')
            ->addGroupBy('project', 'stadsdeel');
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                    ->innerJoin('izHulpvraag.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)",
                    $this->flatten($beginstandBuilder->getQuery()->getResult())
                ));
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                    ->innerJoin('izHulpvraag.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)",
                    $this->flatten($eindstandBuilder->getQuery()->getResult())
                ));
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
        ;
    }

    private function applyReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        switch ($report) {
            case self::REPORT_BEGINSTAND:
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
            case self::REPORT_GESTART:
                $builder
                    ->andWhere('izHulpvraag.koppelingStartdatum >= :startdatum')
                    ->andWhere('izHulpvraag.koppelingStartdatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            case self::REPORT_NIEUW_GESTART:
                $izKlantenBuilder = $this->_em->createQueryBuilder()
                    ->select('izKlant.id')
                    ->from(IzHulpvraag::class, 'izHulpvraag')
                    ->innerJoin('izHulpvraag.izKlant', 'izKlant')
                    ->innerJoin('izHulpvraag.izHulpaanbod', 'izHulpaanbod')
                    ->innerJoin('izKlant.klant', 'klant')
                    ->groupBy('izKlant.id')
                    ->having('MIN(izHulpvraag.koppelingStartdatum) BETWEEN :startdatum AND :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                $builder
                    ->andWhere('izKlant.id IN (:izKlanten)')
                    ->setParameter('izKlanten', $this->flatten($izKlantenBuilder->getQuery()->getResult()))
                ;
                break;
            case self::REPORT_AFGESLOTEN:
                $builder
                    ->andWhere('izHulpvraag.koppelingEinddatum >= :startdatum')
                    ->andWhere('izHulpvraag.koppelingEinddatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            case self::REPORT_EINDSTAND:
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

    private function flatten($values)
    {
        array_walk($values, function (&$item) {
            $item = current($item);
        });

        return $values;
    }
}
