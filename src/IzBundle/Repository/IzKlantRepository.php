<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Hulpvraag;

class IzKlantRepository extends EntityRepository
{
    public const REPORT_BEGINSTAND = 'beginstand';
    public const REPORT_GESTART = 'gestart';
    public const REPORT_NIEUW_GESTART = 'nieuw_gestart';
    public const REPORT_AFGESLOTEN = 'afgesloten';
    public const REPORT_EINDSTAND = 'eindstand';

    public function countTotal($report, \DateTime $startDate, \DateTime $endDate)
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
            ->innerJoin('hulpvraag.project', 'project')
            ->groupBy('project')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.id)")
                    ->innerJoin('hulpvraag.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $this->flatten($beginstandBuilder->getQuery()->getResult());
                if ($beginstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, project.id)", $beginstand));
                }
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.id)")
                    ->innerJoin('hulpvraag.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $eindstand = $this->flatten($eindstandBuilder->getQuery()->getResult());
                if ($eindstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, project.id)", $eindstand));
                }
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $eindstand = null;
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
                $beginstand = $this->flatten($beginstandBuilder->getQuery()->getResult());
                if ($beginstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, werkgebied.naam)", $beginstand));
                }
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $einstand = $this->flatten($eindstandBuilder->getQuery()->getResult());
                if ($eindstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, werkgebied.naam)", $einstand));
                }
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByProjectAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $eindstand = null;
        $builder = $this->getCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('hulpvraag.project', 'project')
            ->addGroupBy('project', 'stadsdeel');
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                    ->innerJoin('hulpvraag.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $this->flatten($beginstandBuilder->getQuery()->getResult());
                if ($beginstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)", $beginstand));
                }
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                    ->innerJoin('hulpvraag.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $einstand = $this->flatten($eindstandBuilder->getQuery()->getResult());
                if ($eindstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)", $einstand));
                }

                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByDoelgroepAndHulpvraagsoort($report, \DateTime $startDate, \DateTime $endDate)
    {
        $eindstand = null;
        $builder = $this->getCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('hulpvraag.project', 'project')
            ->addGroupBy('project', 'stadsdeel');
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                    ->innerJoin('hulpvraag.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $this->flatten($beginstandBuilder->getQuery()->getResult());
                if ($beginstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)", $beginstand));
                }
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                    ->innerJoin('hulpvraag.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $einstand = $this->flatten($eindstandBuilder->getQuery()->getResult());
                if ($eindstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, project.naam, werkgebied.naam)", $einstand));
                }

                break;
        }

        return $builder->getQuery()->getResult();
    }
    private function getCountBuilder()
    {
        return $this->createQueryBuilder('izKlant')
            ->select('COUNT(DISTINCT izKlant.id) AS aantal')
            ->innerJoin('izKlant.klant', 'klant')
            ->innerJoin('izKlant.hulpvragen', 'hulpvraag')
            ->innerJoin('hulpvraag.hulpaanbod', 'hulpaanbod')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
        ;
    }

    private function applyReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        switch ($report) {
            case self::REPORT_BEGINSTAND:
                $builder
                    ->andWhere('hulpvraag.koppelingStartdatum < :startdatum')
                    ->andWhere($builder->expr()->orX(
                        'hulpvraag.koppelingEinddatum IS NULL',
                        "hulpvraag.koppelingEinddatum = '1970-01-01'",
                        'hulpvraag.koppelingEinddatum >= :startdatum'
                    ))
                    ->setParameter('startdatum', $startDate)
                ;
                break;
            case self::REPORT_GESTART:
                $builder
                    ->andWhere('hulpvraag.koppelingStartdatum >= :startdatum')
                    ->andWhere('hulpvraag.koppelingStartdatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            case self::REPORT_NIEUW_GESTART:
                $izKlantenBuilder = $this->_em->createQueryBuilder()
                    ->select('izKlant.id')
                    ->from(Hulpvraag::class, 'hulpvraag')
                    ->innerJoin('hulpvraag.izKlant', 'izKlant')
                    ->innerJoin('hulpvraag.hulpaanbod', 'hulpaanbod')
                    ->innerJoin('izKlant.klant', 'klant')
                    ->groupBy('izKlant.id')
                    ->having('MIN(hulpvraag.koppelingStartdatum) BETWEEN :startdatum AND :einddatum')
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
                    ->andWhere('hulpvraag.koppelingEinddatum >= :startdatum')
                    ->andWhere('hulpvraag.koppelingEinddatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            case self::REPORT_EINDSTAND:
                $builder
                    ->andWhere('hulpvraag.koppelingStartdatum <= :einddatum')
                    ->andWhere($builder->expr()->orX(
                        'hulpvraag.koppelingEinddatum IS NULL',
                        "hulpvraag.koppelingEinddatum = '1970-01-01'",
                        'hulpvraag.koppelingEinddatum > :einddatum'
                    ))
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            default:
                throw new \RuntimeException("Unknown report filter '{$report}' in class ".self::class);
        }
    }

    public function select($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getSelectBuilder();
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

    public function selectByProject($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getSelectBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->innerJoin('hulpvraag.project', 'project')
            ->addGroupBy('project')
            ->orderBy('project.naam')
            ->addOrderBy('klant.achternaam, klant.voornaam, klant.tussenvoegsel')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.id)")
                    ->innerJoin('hulpvraag.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $this->flatten($beginstandBuilder->getQuery()->getResult());
                if ($beginstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, project.id)", $beginstand));
                }
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izKlant.id, project.id)")
                    ->innerJoin('hulpvraag.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $eindstand = $this->flatten($eindstandBuilder->getQuery()->getResult());
                if ($eindstand) {
                    $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', izKlant.id, project.id)", $eindstand));
                }
                break;
        }

        return $builder->getQuery()->getResult();
    }

    private function getSelectBuilder()
    {
        return $this->createQueryBuilder('izKlant')
            ->select('klant.id')
            ->addSelect("CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam) AS naam, COUNT(DISTINCT hulpvraag.id) AS hulpvragen")
            ->innerJoin('izKlant.klant', 'klant')
            ->innerJoin('izKlant.hulpvragen', 'hulpvraag')
            ->innerJoin('hulpvraag.hulpaanbod', 'hulpaanbod')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->addGroupBy('izKlant.id')
            ->orderBy('klant.achternaam, klant.voornaam, klant.tussenvoegsel')
        ;
    }

    private function flatten($values)
    {
        array_walk($values, function (&$item) {
            $item = current($item);
        });

        return $values;
    }
}
