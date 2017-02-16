<?php

namespace OekBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class OekKlantRepository extends EntityRepository
{
    public function countByGroep($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('oekGroep.naam AS groep')
            ->innerJoin('oekKlant.oekGroepen', 'oekGroep')
            ->groupBy('oekGroep')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

//         switch ($report) {
//             case 'gestart':
//                 // exclude beginstand
//                 $beginstandBuilder = $this->getCountBuilder()
//                     ->select("CONCAT_WS('-', oekKlant.id, oekProject.id)")
//                     ->innerJoin('oekHulpvraag.oekProject', 'oekProject')
//                 ;
//                 $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
//                 $beginstand = $beginstandBuilder->getQuery()->getResult();
//                 array_walk($beginstand, function (&$item) {
//                     $item = current($item);
//                 });
//                 $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', oekKlant.id, oekProject.id)", $beginstand));
//                 break;
//             case 'afgesloten':
//                 // exclude eindstand
//                 $eindstandBuilder = $this->getCountBuilder()
//                     ->select("CONCAT_WS('-', oekKlant.id, oekProject.id)")
//                     ->innerJoin('oekHulpvraag.oekProject', 'oekProject')
//                 ;
//                 $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
//                 $eindstand = $eindstandBuilder->getQuery()->getResult();
//                 // flatten array
//                 array_walk($eindstand, function (&$item) {
//                     $item = current($item);
//                 });
//                 $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', oekKlant.id, oekProject.id)", $eindstand));
//                 break;
//         }

        return $builder->getQuery()->getResult();
    }

    public function countByTraining($report, \DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->getCountBuilder()
            ->addSelect('oekTraining.naam AS training')
            ->innerJoin('oekKlant.oekTrainingen', 'oekTraining')
            ->groupBy('oekTraining')
        ;
        $this->applyReportFilter($builder, $report, $startDate, $endDate);

//         switch ($report) {
//             case 'gestart':
//                 // exclude beginstand
//                 $beginstandBuilder = $this->getCountBuilder()
//                     ->select("CONCAT_WS('-', oekKlant.id, oekProject.id)")
//                     ->innerJoin('oekHulpvraag.oekProject', 'oekProject')
//                 ;
//                 $this->applyReportFilter($beginstandBuilder, 'beginstand', $startDate, $endDate);
//                 $beginstand = $beginstandBuilder->getQuery()->getResult();
//                 array_walk($beginstand, function (&$item) {
//                     $item = current($item);
//                 });
//                 $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', oekKlant.id, oekProject.id)", $beginstand));
//                 break;
//             case 'afgesloten':
//                 // exclude eindstand
//                 $eindstandBuilder = $this->getCountBuilder()
//                     ->select("CONCAT_WS('-', oekKlant.id, oekProject.id)")
//                     ->innerJoin('oekHulpvraag.oekProject', 'oekProject')
//                 ;
//                 $this->applyReportFilter($eindstandBuilder, 'eindstand', $startDate, $endDate);
//                 $eindstand = $eindstandBuilder->getQuery()->getResult();
//                 // flatten array
//                 array_walk($eindstand, function (&$item) {
//                     $item = current($item);
//                 });
//                 $builder->andWhere($builder->expr()->notIn("CONCAT_WS('-', oekKlant.id, oekProject.id)", $eindstand));
//                 break;
//         }

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder()
    {
        return $this->createQueryBuilder('oekKlant')
            ->select('COUNT(DISTINCT oekKlant.id) AS aantal')
            ->innerJoin('oekKlant.klant', 'klant')
//             ->innerJoin('oekKlant.oekHulpvragen', 'oekHulpvraag')
//             ->innerJoin('oekHulpvraag.oekHulpaanbod', 'oekHulpaanbod')
//             ->innerJoin('oekHulpaanbod.oekVrijwilliger', 'oekVrijwilliger')
//             ->innerJoin('oekVrijwilliger.vrijwilliger', 'vrijwilliger')
//             ->leftJoin('oekKlant.oekAfsluiting', 'oekAfsluiting')
//             ->andWhere('oekAfsluiting.id IS NULL OR oekAfsluiting.naam <> :foutieve_invoer')
//             ->setParameter('foutieve_invoer', 'Foutieve invoer')
        ;
    }

    private function applyReportFilter(QueryBuilder $builder, $report, \DateTime $startDate, \DateTime $endDate)
    {
        //         switch ($report) {
//             case 'beginstand':
//                 $builder
//                     ->andWhere('oekHulpvraag.koppelingStartdatum < :startdatum')
//                     ->andWhere($builder->expr()->orX(
//                         'oekHulpvraag.koppelingEinddatum IS NULL',
//                         "oekHulpvraag.koppelingEinddatum = '0000-00-00'",
//                         'oekHulpvraag.koppelingEinddatum >= :startdatum'
//                     ))
//                     ->setParameter('startdatum', $startDate)
//                 ;
//                 break;
//             case 'gestart':
//                 $builder
//                     ->andWhere('oekHulpvraag.koppelingStartdatum >= :startdatum')
//                     ->andWhere('oekHulpvraag.koppelingStartdatum <= :einddatum')
//                     ->setParameter('startdatum', $startDate)
//                     ->setParameter('einddatum', $endDate)
//                 ;
//                 break;
//             case 'afgesloten':
//                 $builder
//                     ->andWhere('oekHulpvraag.koppelingEinddatum >= :startdatum')
//                     ->andWhere('oekHulpvraag.koppelingEinddatum <= :einddatum')
//                     ->setParameter('startdatum', $startDate)
//                     ->setParameter('einddatum', $endDate)
//                 ;
//                 break;
//             case 'eindstand':
//                 $builder
//                     ->andWhere('oekHulpvraag.koppelingStartdatum <= :einddatum')
//                     ->andWhere($builder->expr()->orX(
//                         'oekHulpvraag.koppelingEinddatum IS NULL',
//                         "oekHulpvraag.koppelingEinddatum = '0000-00-00'",
//                         'oekHulpvraag.koppelingEinddatum > :einddatum'
//                     ))
//                     ->setParameter('einddatum', $endDate)
//                 ;
//                 break;
//             default:
//                 throw new \RuntimeException("Unknown report filter '{$report}' in class ".__CLASS__);
//         }
    }
}
