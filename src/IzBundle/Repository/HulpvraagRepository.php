<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class HulpvraagRepository extends EntityRepository
{
    public function countSuccesindicatorenByHulpvraagsoort(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('hulpvraagsoort.naam AS hulpvraagsoortnaam')
            ->addSelect('succesindicator.naam AS succesindicatornaam')
            ->innerJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort')
            ->innerJoin('hulpvraag.succesindicatoren', 'succesindicator')
            ->groupBy('hulpvraagsoort, succesindicator');
        $this->applyKoppelingenReportFilter($builder, 'afgesloten', $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countDoelgroepenByHulpvraagsoort(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('hulpvraagsoort.naam AS hulpvraagsoortnaam')
            ->addSelect('doelgroepen.naam AS doelgroepnaam')
            ->innerJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort')
            ->innerJoin('hulpvraag.doelgroepen', 'doelgroepen')
            ->groupBy('doelgroepen', 'hulpvraagsoort');
        $this->applyKoppelingenReportFilter($builder, 'gestart', $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countHulpvragenByProjectAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getHulpvragenCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('hulpvraag.project', 'project')
            ->groupBy('project', 'stadsdeel');
        $this->applyHulpvragenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingen($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder();
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByAfsluitreden($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('afsluitredenKoppeling.naam AS afsluitreden')
            ->innerJoin('hulpaanbod.afsluitredenKoppeling', 'afsluitredenKoppeling')
            ->groupBy('afsluitreden')
        ;
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByProjectAndAfsluitreden($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->addSelect('afsluitredenKoppeling.naam AS afsluitreden')
            ->innerJoin('hulpaanbod.afsluitredenKoppeling', 'afsluitredenKoppeling')
            ->innerJoin('hulpvraag.project', 'project')
            ->groupBy('project', 'afsluitreden')
        ;
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByCoordinator($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect("CONCAT_WS(' ', medewerker.voornaam, medewerker.tussenvoegsel, medewerker.achternaam) AS coordinator")
            ->innerJoin('hulpaanbod.medewerker', 'medewerker')
            ->groupBy('medewerker')
            ->orderBy('medewerker.voornaam');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByProject($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->innerJoin('hulpvraag.project', 'project')
            ->groupBy('project');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByDoelgroep($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('doelgroep.naam AS doelgroepnaam')
            ->innerJoin('hulpvraag.doelgroepen', 'doelgroep')
            ->groupBy('doelgroep');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->groupBy('stadsdeel');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByPostcodegebied($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('ggwgebied.naam AS ggwgebiednaam')
            ->leftJoin('klant.postcodegebied', 'ggwgebied')
            ->groupBy('ggwgebied');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

//    public function countKoppelingenByDoelgroepAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
//    {
//        $builder = $this->getKoppelingenCountBuilder()
//            ->addSelect('doelgroep.naam AS doelgroepnaam')
//            ->addSelect('werkgebied.naam AS stadsdeel')
//            ->leftJoin('klant.werkgebied', 'werkgebied')
//            ->innerJoin('hulpvraag.doelgroepen', 'doelgroep')
//            ->groupBy('project', 'stadsdeel');
//        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);
//
//        return $builder->getQuery()->getResult();
//    }

    public function countKoppelingenByProjectAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('hulpvraag.project', 'project')
            ->groupBy('project', 'stadsdeel');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByProjectAndPostcodegebied($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('project.naam AS projectnaam')
            ->addSelect('ggwgebied.naam AS ggwgebiednaam')
            ->innerJoin('hulpvraag.project', 'project')
            ->leftJoin('klant.postcodegebied', 'ggwgebied')
            ->groupBy('project', 'ggwgebied');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByHulpvraagsoortAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('hulpvraagsoort.naam AS hulpvraagsoortnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort')
            ->groupBy('hulpvraagsoort', 'stadsdeel');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByHulpvraagsoortAndPostcodegebied($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('hulpvraagsoort.naam AS hulpvraagsoortnaam')
            ->addSelect('ggwgebied.naam AS ggwgebiednaam')
            ->leftJoin('klant.postcodegebied', 'ggwgebied')
            ->innerJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort')
            ->groupBy('hulpvraagsoort', 'ggwgebied');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByDoelgroepAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('doelgroep.naam AS doelgroepnaam')
            ->addSelect('werkgebied.naam AS stadsdeel')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('hulpvraag.doelgroepen', 'doelgroep')
            ->groupBy('doelgroep', 'stadsdeel');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByDoelgroepAndPostcodegebied($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('doelgroep.naam AS doelgroepnaam')
            ->addSelect('ggwgebied.naam AS ggwgebiednaam')
            ->leftJoin('klant.postcodegebied', 'ggwgebied')
            ->innerJoin('hulpvraag.doelgroepen', 'doelgroep')
            ->groupBy('doelgroep', 'ggwgebied');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function getHulpvragenCountBuilder()
    {
        return $this->createQueryBuilder('hulpvraag')
            ->select('COUNT(hulpvraag.id) AS aantal')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
        ;
    }

    private function getKoppelingenCountBuilder()
    {
        return $this->createQueryBuilder('hulpvraag')
            ->select('COUNT(hulpvraag.id) AS aantal')
            ->innerJoin('hulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
            ->innerJoin('hulpvraag.hulpaanbod', 'hulpaanbod')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
        ;
    }

    private function applyHulpvragenReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        // use hulpvraag.startdatum by default, but use hulpvraag.created if necessary
        $startdatumDql = "CASE WHEN hulpvraag.startdatum IS NULL OR hulpvraag.startdatum = '0000-00-00'
            THEN hulpvraag.created ELSE hulpvraag.startdatum END";

        // use hulpvraag.einddatum by default, but use hulpvraag.koppelingEinddatum if necessary
        $einddatumDql = "CASE WHEN (hulpvraag.einddatum IS NULL OR hulpvraag.startdatum = '0000-00-00')
            AND hulpvraag.koppelingEinddatum IS NOT NULL
            AND hulpvraag.koppelingEinddatum <> '0000-00-00'
            THEN hulpvraag.koppelingEinddatum ELSE hulpvraag.einddatum END";

        // special case because WHERE (CASE WHEN ... THEN ... ELSE ... END) IS NULL does not work in DQL (while it does in SQL)
        $einddatumIsNullDql = "CASE WHEN (hulpvraag.einddatum IS NULL OR hulpvraag.einddatum = '0000-00-00')
            AND (hulpvraag.koppelingEinddatum IS NULL OR hulpvraag.koppelingEinddatum = '0000-00-00')
            THEN 0 ELSE 1 END";

        switch ($report) {
            case 'beginstand':
            $builder->andWhere("{$startdatumDql} < :startdatum")
                ->andWhere($builder->expr()->orX(
                    "{$einddatumIsNullDql} = 0",
                    "{$einddatumDql} = '0000-00-00'",
                    "{$einddatumDql} >= :startdatum"
                ))
                ->setParameter('startdatum', $startDate);
            break;
            case 'gestart':
                $builder->andWhere("{$startdatumDql} >= :startdatum")
                    ->andWhere("{$startdatumDql} <= :einddatum")
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate);
                break;
            case 'afgesloten':
                $builder->andWhere("{$einddatumDql} >= :startdatum")
                    ->andWhere("{$einddatumDql} <= :einddatum")
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate);
                break;
            case 'eindstand':
                $builder->andWhere('hulpvraag.startdatum <= :einddatum')
                    ->andWhere($builder->expr()->orX(
                        "{$einddatumIsNullDql} = 0",
                        "{$einddatumDql} = '0000-00-00'",
                        "{$einddatumDql} > :einddatum"
                    ))
                    ->setParameter('einddatum', $endDate);
                break;
            default:
                throw new \RuntimeException("Unknown report filter '{$report}' in class ".__CLASS__);
        }
    }

    private function applyKoppelingenReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        switch ($report) {
            case 'beginstand':
                $builder->andWhere('hulpvraag.koppelingStartdatum < :startdatum')
                    ->andWhere($builder->expr()->orX(
                        'hulpvraag.koppelingEinddatum IS NULL',
                        "hulpvraag.koppelingEinddatum = '0000-00-00'",
                        'hulpvraag.koppelingEinddatum >= :startdatum'
                    ))
                    ->setParameter('startdatum', $startDate);
                break;
            case 'gestart':
                $builder->andWhere('hulpvraag.koppelingStartdatum >= :startdatum')
                    ->andWhere('hulpvraag.koppelingStartdatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate);
                break;
            case 'afgesloten':
                $builder->andWhere('hulpvraag.koppelingEinddatum >= :startdatum')
                    ->andWhere('hulpvraag.koppelingEinddatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate);
                break;
            case 'succesvol_afgesloten':
                $builder->andWhere('hulpvraag.koppelingEinddatum >= :startdatum')
                    ->andWhere('hulpvraag.koppelingEinddatum <= :einddatum')
                    ->andWhere('hulpvraag.koppelingSuccesvol = true')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate);
                break;
            case 'eindstand':
                $builder->andWhere('hulpvraag.koppelingStartdatum <= :einddatum')
                    ->andWhere($builder->expr()->orX(
                        'hulpvraag.koppelingEinddatum IS NULL',
                        "hulpvraag.koppelingEinddatum = '0000-00-00'",
                        'hulpvraag.koppelingEinddatum > :einddatum'
                    ))
                    ->setParameter('einddatum', $endDate);
                break;
            default:
                throw new \RuntimeException("Unknown report filter '{$report}' in class ".__CLASS__);
        }
    }
}
