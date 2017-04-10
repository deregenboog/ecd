<?php

namespace IzBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Postcodegebied;

class IzHulpvraagRepository extends EntityRepository
{
    public function countHulpvragenByProjectAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getHulpvragenCountBuilder()
            ->addSelect('izProject.naam AS project')
            ->addSelect('klant.werkgebied AS stadsdeel')
            ->innerJoin('izHulpvraag.izProject', 'izProject')
            ->groupBy('izProject', 'klant.werkgebied');
        $this->applyHulpvragenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingen($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder();
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByCoordinator($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect("CONCAT_WS(' ', medewerker.voornaam, medewerker.tussenvoegsel, medewerker.achternaam) AS coordinator")
            ->innerJoin('izHulpaanbod.medewerker', 'medewerker')
            ->groupBy('medewerker')
            ->orderBy('medewerker.voornaam');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByProject($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('izProject.naam AS project')
            ->innerJoin('izHulpaanbod.izProject', 'izProject')
            ->groupBy('izProject');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('klant.werkgebied AS stadsdeel')
            ->groupBy('klant.werkgebied');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByPostcodegebied($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('postcodegebied.postcodegebied')
            ->innerJoin(Postcodegebied::class, 'postcodegebied', 'WITH', 'klant.postcode BETWEEN postcodegebied.van AND postcodegebied.tot')
            ->groupBy('postcodegebied');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByProjectAndStadsdeel($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('izProject.naam AS project')
            ->addSelect('klant.werkgebied AS stadsdeel')
            ->innerJoin('izHulpaanbod.izProject', 'izProject')
            ->groupBy('izProject', 'klant.werkgebied');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    public function countKoppelingenByProjectAndPostcodegebied($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getKoppelingenCountBuilder()
            ->addSelect('izProject.naam AS project')
            ->addSelect('postcodegebied.postcodegebied')
            ->innerJoin('izHulpaanbod.izProject', 'izProject')
            ->innerJoin(Postcodegebied::class, 'postcodegebied', 'WITH', 'klant.postcode BETWEEN postcodegebied.van AND postcodegebied.tot')
            ->groupBy('izProject', 'postcodegebied');
        $this->applyKoppelingenReportFilter($builder, $report, $startDate, $endDate);

        return $builder->getQuery()->getResult();
    }

    private function getHulpvragenCountBuilder()
    {
        return $this->createQueryBuilder('izHulpvraag')
            ->select('COUNT(izHulpvraag.id) AS aantal')
            ->innerJoin('izHulpvraag.izKlant', 'izKlant')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('izHulpvraag.izEindeKoppeling', 'izEindeKoppelingHulpvraag')
            ->leftJoin('izKlant.izAfsluiting', 'izAfsluitingKlant')
            ->andWhere('izAfsluitingKlant.id IS NULL OR izAfsluitingKlant.naam <> :foutieve_invoer')
            ->andWhere('izEindeKoppelingHulpvraag.id IS NULL OR izEindeKoppelingHulpvraag.naam <> :foutieve_invoer')
            ->setParameter('foutieve_invoer', 'Foutieve invoer');
    }

    private function getKoppelingenCountBuilder()
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
            ->setParameter('foutieve_invoer', 'Foutieve invoer');
    }

    private function applyHulpvragenReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        // use izHulpvraag.startdatum by default, but use izHulpvraag.created if necessary
        $startdatumDql = "CASE WHEN izHulpvraag.startdatum IS NULL OR izHulpvraag.startdatum = '0000-00-00'
            THEN izHulpvraag.created ELSE izHulpvraag.startdatum END";

        // use izHulpvraag.einddatum by default, but use izHulpvraag.koppelingEinddatum if necessary
        $einddatumDql = "CASE WHEN (izHulpvraag.einddatum IS NULL OR izHulpvraag.startdatum = '0000-00-00')
            AND izHulpvraag.koppelingEinddatum IS NOT NULL
            AND izHulpvraag.koppelingEinddatum <> '0000-00-00'
            THEN izHulpvraag.koppelingEinddatum ELSE izHulpvraag.einddatum END";

        // special case because WHERE (CASE WHEN ... THEN ... ELSE ... END) IS NULL does not work in DQL (while it does in SQL)
        $einddatumIsNullDql = "CASE WHEN (izHulpvraag.einddatum IS NULL OR izHulpvraag.einddatum = '0000-00-00')
            AND (izHulpvraag.koppelingEinddatum IS NULL OR izHulpvraag.koppelingEinddatum = '0000-00-00')
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
                $builder->andWhere('izHulpvraag.startdatum <= :einddatum')
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
                $builder->andWhere('izHulpvraag.koppelingStartdatum < :startdatum')
                    ->andWhere($builder->expr()->orX(
                        'izHulpvraag.koppelingEinddatum IS NULL',
                        "izHulpvraag.koppelingEinddatum = '0000-00-00'",
                        'izHulpvraag.koppelingEinddatum >= :startdatum'
                    ))
                    ->setParameter('startdatum', $startDate);
                break;
            case 'gestart':
                $builder->andWhere('izHulpvraag.koppelingStartdatum >= :startdatum')
                    ->andWhere('izHulpvraag.koppelingStartdatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate);
                break;
            case 'afgesloten':
                $builder->andWhere('izHulpvraag.koppelingEinddatum >= :startdatum')
                    ->andWhere('izHulpvraag.koppelingEinddatum <= :einddatum')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate);
                break;
            case 'succesvol_afgesloten':
                $builder->andWhere('izHulpvraag.koppelingEinddatum >= :startdatum')
                    ->andWhere('izHulpvraag.koppelingEinddatum <= :einddatum')
                    ->andWhere('izHulpvraag.koppelingSuccesvol = true')
                    ->setParameter('startdatum', $startDate)
                    ->setParameter('einddatum', $endDate);
                break;
            case 'eindstand':
                $builder->andWhere('izHulpvraag.koppelingStartdatum <= :einddatum')
                    ->andWhere($builder->expr()->orX(
                        'izHulpvraag.koppelingEinddatum IS NULL',
                        "izHulpvraag.koppelingEinddatum = '0000-00-00'",
                        'izHulpvraag.koppelingEinddatum > :einddatum'
                    ))
                    ->setParameter('einddatum', $endDate);
                break;
            default:
                throw new \RuntimeException("Unknown report filter '{$report}' in class ".__CLASS__);
        }
    }
}
