<?php

namespace MwBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Service\AbstractDao;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\BinnenVia;
use MwBundle\Entity\BinnenViaOptieVW;
use MwBundle\Entity\MwDossierStatus;

class MwDossierStatusDao extends AbstractDao
{
    protected $class = MwDossierStatus::class;

    protected $alias = 'dossierstatus';

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}.
     */
    public function create($entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update($entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete($entity)
    {
        $this->doDelete($entity);
    }

    public function findAllAanmeldingenForLocaties($startDate,$endDate, $locaties = [])
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder
            ->select("COUNT(dossierstatus) AS aantal, locatie.naam AS naam")
            ->join("dossierstatus.locatie","locatie")
            ->where("locatie.naam IN (:locatienamen)")
            ->andWhere("dossierstatus.datum BETWEEN :startdate AND :enddate")
            ->andWhere("dossierstatus INSTANCE OF :class")
            ->groupBy("locatie.naam")
            ->orderBy("locatie.naam","ASC")
            ->setParameter("locatienamen",$locaties)
            ->setParameter("startdate",$startDate)
            ->setParameter("enddate",$endDate)
            ->setParameter("class","Aanmelding")
            ;
        return $builder->getQuery()->getResult();

    }

    public function findAllAanmeldingenBinnenVia($startDate,$endDate, $locaties = [])
    {
        $builder = $this->repository->createQueryBuilder($this->alias);
        $builder
            ->select("COUNT(dossierstatus) AS aantal, binnenviaoptie.naam AS naam")
            ->join("dossierstatus.locatie","locatie")
            ->join("dossierstatus.binnenViaOptieKlant","binnenviaoptie")
            ->where("locatie.naam IN (:locatienamen)")
            ->andWhere("dossierstatus.datum BETWEEN :startdate AND :enddate")
            ->andWhere("dossierstatus INSTANCE OF :class")
            ->groupBy("binnenviaoptie.naam")
            ->orderBy('binnenviaoptie.naam','ASC')
            ->setParameter("locatienamen",$locaties)
            ->setParameter("startdate",$startDate)
            ->setParameter("enddate",$endDate)
            ->setParameter("class","Aanmelding")
        ;
        return $builder->getQuery()->getResult();

    }

    /**
     * Vind alle klanten die in de betreffende periode actief waren, dus ze waren al actief voor de periode, en zijn in de periode afgesloten
     * of ze zijn actief geworden in de periode, en nog niet afgesloten
     * of ze zijn aangemeld en afgesloten in de periode.
     * @param $startDate
     * @param $endDate
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    public function getActiveKlantIdsForPeriod($startDate,$endDate):array
    {
        $sql = "WITH EventPairs AS (
    SELECT
        id,
        klant_id,
        reden_id,
        locatie_id,
        project_id,
        class,
        datum,
        LAG(datum) OVER (PARTITION BY klant_id ORDER BY datum) AS prev_datum,
        LAG(class) OVER (PARTITION BY klant_id ORDER BY datum) AS prev_class,
        LEAD(class) OVER (PARTITION BY klant_id ORDER BY datum) AS next_class
       
    FROM
        mw_dossier_statussen 
        
)
SELECT
 DISTINCT e.klant_id
FROM
    EventPairs e
  
WHERE
        (
          (e.class = 'Afsluiting'
              AND e.prev_class = 'Aanmelding'
          )
          OR
          (e.class = 'Aanmelding'
              AND e.next_class IS NULL
          )
          AND datum BETWEEN :startdate AND :enddate
      )

";

        $statement = $this->entityManager->getConnection()->prepare($sql);
        $statement->bindValue(":startdate",$startDate,"datetime");
        $statement->bindValue(":enddate",$endDate,"datetime");

        $result = $statement->executeQuery();

        $aar = $result->fetchAllAssociative();

        /**
         * As result cannot return a simple array but only associative, map the klant_id field as the value so it gets one dimension.
         */
        array_walk($aar,function(&$v,$k){
            $v = $v['klant_id'];
        });
        return $aar;
    }

    public function findDoorlooptijdForLocaties($startDate,$endDate, $locaties = [])
    {

        $locatiesStr = "'".implode("','",$locaties)."'";

        $sql = "WITH EventPairs AS (
    SELECT
        id,
        klant_id,
        reden_id,
        locatie_id,
        project_id,
        class,
        datum,
        LAG(datum) OVER (PARTITION BY klant_id ORDER BY datum) AS prev_datum,
        LAG(class) OVER (PARTITION BY klant_id ORDER BY datum) AS prev_class
    FROM
        mw_dossier_statussen 
)
SELECT
    mar.naam AS naam,
    count(klant_id) AS aantal,
    ROUND(AVG(DATEDIFF(e.datum, e.prev_datum))) AS avg_duration
FROM
    EventPairs e
    INNER JOIN mw_afsluitredenen_klanten mar ON mar.id = e.reden_id
    INNER JOIN locaties l ON l.id = e.locatie_id
WHERE
        e.class = 'Afsluiting'
  AND e.prev_class = 'Aanmelding'
AND datum BETWEEN :startdate AND :enddate
AND l.naam IN ($locatiesStr)

GROUP BY reden_id"; //wrap into subquery.

        $statement = $this->entityManager->getConnection()->prepare($sql);
        $statement->bindValue(":startdate",$startDate,"datetime");
        $statement->bindValue(":enddate",$endDate,"datetime");

        $result = $statement->executeQuery();


        return $result->fetchAllAssociative();

    }

    public function findAllAfsluitredenenAfgeslotenKlantenForLocaties($startDate,$endDate, $locaties = [])
    {

        $locatiesStr = "'".implode("','",$locaties)."'";

        $sql = "WITH EventPairs AS (
    SELECT
        id,
        klant_id,
        reden_id,
        locatie_id,
        project_id,
        class,
        datum,
        LAG(datum) OVER (PARTITION BY klant_id ORDER BY datum) AS prev_datum,
        LAG(class) OVER (PARTITION BY klant_id ORDER BY datum) AS prev_class
    FROM
        mw_dossier_statussen 
)
SELECT
    mar.naam AS naam,
    count(klant_id) AS aantal
FROM
    EventPairs e
    INNER JOIN mw_afsluitredenen_klanten mar ON mar.id = e.reden_id
    INNER JOIN locaties l ON l.id = e.locatie_id
WHERE
        e.class = 'Afsluiting'
  AND e.prev_class = 'Aanmelding'
AND datum BETWEEN :startdate AND :enddate
AND l.naam IN ($locatiesStr)

GROUP BY reden_id"; //wrap into subquery.

        $statement = $this->entityManager->getConnection()->prepare($sql);
        $statement->bindValue(":startdate",$startDate,"datetime");
        $statement->bindValue(":enddate",$endDate,"datetime");

        $result = $statement->executeQuery();


        return $result->fetchAllAssociative();

    }
}
