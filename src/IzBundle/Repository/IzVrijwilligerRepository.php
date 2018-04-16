<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\MatchingKlant;
use IzBundle\Entity\Hulpaanbod;

class IzVrijwilligerRepository extends EntityRepository
{
    const REPORT_BEGINSTAND = 'beginstand';
    const REPORT_GESTART = 'gestart';
    const REPORT_NIEUW_GESTART = 'nieuw_gestart';
    const REPORT_AFGESLOTEN = 'afgesloten';
    const REPORT_EINDSTAND = 'eindstand';

    public function count($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder();
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()->select('izVrijwilliger.id');
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $beginstandBuilder->getQuery()->getResult();
                $builder->andWhere('izVrijwilliger.id NOT IN (:beginstand)')->setParameter('beginstand', $beginstand);
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()->select('izVrijwilliger.id');
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $eindstand = $eindstandBuilder->getQuery()->getResult();
                $builder->andWhere('izVrijwilliger.id NOT IN (:eindstand)')->setParameter('eindstand', $eindstand);
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByProject($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->innerJoin('hulpaanbod.project', 'project')
            ->addGroupBy('project')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, project.id)")
                    ->innerJoin('hulpaanbod.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, project.id)",
                    $this->flatten($beginstandBuilder->getQuery()->getResult())
                ));
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, project.id)")
                    ->innerJoin('hulpaanbod.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, project.id)",
                    $this->flatten($eindstandBuilder->getQuery()->getResult())
                ));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByStadsdeelKlant($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->addGroupBy('stadsdeel')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, werkgebied.naam)",
                    $this->flatten($beginstandBuilder->getQuery()->getResult())
                ));
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, werkgebied.naam)",
                    $this->flatten($eindstandBuilder->getQuery()->getResult())
                ));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByStadsdeelVrijwilliger($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
            ->addGroupBy('stadsdeel')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, werkgebied.naam)")
                    ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, werkgebied.naam)",
                    $this->flatten($beginstandBuilder->getQuery()->getResult())
                ));
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, werkgebied.naam)")
                    ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, werkgebied.naam)",
                    $this->flatten($eindstandBuilder->getQuery()->getResult())
                ));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByProjectAndStadsdeelKlant($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('hulpaanbod.project', 'project')
            ->addGroupBy('project', 'stadsdeel')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                ->select("CONCAT_WS('-', izVrijwilliger.id, project.naam, werkgebied.naam)")
                ->leftJoin('klant.werkgebied', 'werkgebied')
                ->innerJoin('hulpaanbod.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, project.naam, werkgebied.naam)",
                    $this->flatten($beginstandBuilder->getQuery()->getResult())
                    ));
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, project.naam, werkgebied.naam)")
                    ->leftJoin('klant.werkgebied', 'werkgebied')
                    ->innerJoin('hulpaanbod.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, project.naam, werkgebied.naam)",
                    $this->flatten($eindstandBuilder->getQuery()->getResult())
                ));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function countByProjectAndStadsdeelVrijwilliger($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
            ->innerJoin('hulpaanbod.project', 'project')
            ->addGroupBy('project', 'stadsdeel')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, project.naam, werkgebied.naam)")
                    ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
                    ->innerJoin('hulpaanbod.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, project.naam, werkgebied.naam)",
                    $this->flatten($beginstandBuilder->getQuery()->getResult())
                ));
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, project.naam, werkgebied.naam)")
                    ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
                    ->innerJoin('hulpaanbod.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, project.naam, werkgebied.naam)",
                    $this->flatten($eindstandBuilder->getQuery()->getResult())
                ));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('izVrijwilliger')
            ->select('COUNT(DISTINCT izVrijwilliger.id) AS aantal')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->innerJoin('izVrijwilliger.izHulpaanbiedingen', 'hulpaanbod')
            ->innerJoin('hulpaanbod.hulpvraag', 'hulpvraag')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
        ;
    }

    private function applyReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        switch ($report) {
            case self::REPORT_BEGINSTAND:
                $builder
                    ->andWhere('hulpaanbod.koppelingStartdatum < :startdatum')
                    ->andWhere($builder->expr()->orX(
                        'hulpaanbod.koppelingEinddatum IS NULL',
                        "hulpaanbod.koppelingEinddatum = '0000-00-00'",
                        'hulpaanbod.koppelingEinddatum >= :startdatum'
                    ))
                    ->setParameter('startdatum', $startDate)
                ;
                break;
            case self::REPORT_GESTART:
                $builder
                    ->andWhere('hulpaanbod.koppelingStartdatum >= :startdatum')
                    ->andWhere('hulpaanbod.koppelingStartdatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            case self::REPORT_NIEUW_GESTART:
                $izVrijwilligersBuilder = $this->_em->createQueryBuilder()
                    ->select('izVrijwilliger.id')
                    ->from(Hulpaanbod::class, 'hulpaanbod')
                    ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
                    ->innerJoin('hulpaanbod.hulpvraag', 'hulpvraag')
                    ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
                    ->groupBy('izVrijwilliger.id')
                    ->having('MIN(hulpaanbod.koppelingStartdatum) BETWEEN :startdatum AND :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                $builder
                    ->andWhere('izVrijwilliger.id IN (:izVrijwilligers)')
                    ->setParameter('izVrijwilligers', $this->flatten($izVrijwilligersBuilder->getQuery()->getResult()))
                ;
                break;
            case self::REPORT_AFGESLOTEN:
                $builder
                    ->andWhere('hulpaanbod.koppelingEinddatum >= :startdatum')
                    ->andWhere('hulpaanbod.koppelingEinddatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            case self::REPORT_EINDSTAND:
                $builder
                    ->andWhere('hulpaanbod.koppelingStartdatum <= :einddatum')
                    ->andWhere($builder->expr()->orX(
                        'hulpaanbod.koppelingEinddatum IS NULL',
                        "hulpaanbod.koppelingEinddatum = '0000-00-00'",
                        'hulpaanbod.koppelingEinddatum > :einddatum'
                    ))
                    ->setParameter('einddatum', $endDate)
                ;
                break;
            default:
                throw new \RuntimeException("Unknown report filter '{$report}' in class ".__CLASS__);
        }
    }

    public function select($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getSelectBuilder();
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()->select('izVrijwilliger.id');
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $beginstand = $beginstandBuilder->getQuery()->getResult();
                $builder->andWhere('izVrijwilliger.id NOT IN (:beginstand)')->setParameter('beginstand', $beginstand);
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()->select('izVrijwilliger.id');
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $eindstand = $eindstandBuilder->getQuery()->getResult();
                $builder->andWhere('izVrijwilliger.id NOT IN (:eindstand)')->setParameter('eindstand', $eindstand);
                break;
        }

        return $builder->getQuery()->getResult();
    }

    public function selectByProject($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getSelectBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->innerJoin('hulpaanbod.project', 'project')
            ->addGroupBy('project')
            ->orderBy('project.naam')
            ->addOrderBy('vrijwilliger.achternaam, vrijwilliger.voornaam, vrijwilliger.tussenvoegsel')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

        switch ($report) {
            case self::REPORT_GESTART:
                // exclude beginstand
                $beginstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, project.id)")
                    ->innerJoin('hulpaanbod.project', 'project')
                ;
                $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, project.id)",
                    $this->flatten($beginstandBuilder->getQuery()->getResult())
                ));
                break;
            case self::REPORT_AFGESLOTEN:
                // exclude eindstand
                $eindstandBuilder = $this->getCountBuilder()
                    ->select("CONCAT_WS('-', izVrijwilliger.id, project.id)")
                    ->innerJoin('hulpaanbod.project', 'project')
                ;
                $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
                $builder->andWhere($builder->expr()->notIn(
                    "CONCAT_WS('-', izVrijwilliger.id, project.id)",
                    $this->flatten($eindstandBuilder->getQuery()->getResult())
                ));
                break;
        }

        return $builder->getQuery()->getResult();
    }

    private function getSelectBuilder()
    {
        return $this->createQueryBuilder('izVrijwilliger')
            ->select('vrijwilliger.id')
            ->addSelect("CONCAT_WS(' ', vrijwilliger.voornaam, vrijwilliger.tussenvoegsel, vrijwilliger.achternaam) AS naam, COUNT(DISTINCT hulpaanbod.id) AS hulpaanbiedingen")
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->innerJoin('izVrijwilliger.izHulpaanbiedingen', 'hulpaanbod')
            ->innerJoin('hulpaanbod.hulpvraag', 'hulpvraag')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
            ->addGroupBy('izVrijwilliger.id')
            ->orderBy('vrijwilliger.achternaam, vrijwilliger.voornaam, vrijwilliger.tussenvoegsel')
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
