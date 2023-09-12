<?php

namespace MwBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\QueryBuilder;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Verslag;

class VerslagDao extends AbstractDao implements VerslagDaoInterface
{
    protected $class = Verslag::class;

    protected $alias = 'verslag';

    public function create(Verslag $entity)
    {
//        if($entity->getKlant()->getHuidigeMwStatus() == null)
//        {
//            $mwAanmelding = new Aanmelding($entity->getKlant(),$entity->getMedewerker());
//            $entity->getKlant()->setHuidigeMwStatus($mwAanmelding);
//
//        }
        $this->doCreate($entity);


    }

    public function update(Verslag $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Verslag $entity)
    {
        $this->doDelete($entity);
    }


    public function countUniqueKlantenVoorLocaties(
        \DateTime $startdatum,
        \DateTime $einddatum,
        $locatieNamen
    ) {


        $builder = $this->repository->createQueryBuilder('verslagen');

        $builder = self::buildUniqueKlantenVoorLocatiesQuery($builder,$startdatum,$einddatum,$locatieNamen);

//            ->setParameter("totKlant",5)
            ;

//        $sql = SqlExtractor::getFullSQL($builder->getQuery());

        return $builder->getQuery();

    }

    public function countKlantenZonderZorg(\DateTime $startdatum,\DateTime $einddatum,$locatieNamen)
    {
        /**
         * SELECT SUM(c.c) AS numContacten, SUM(d.c) FROM (SELECT COUNT(id) AS c FROM `verslagen` `v` WHERE `v`.`locatie_id` = 5 AND `v`.`contactsoort_id` = 1 AND v.`datum` between '2018-01-01' AND '2019-01-01'
        GROUP BY klant_id
        HAVING COUNT(klant_id) < 5) AS c
         */

        $query = "
        (SELECT SUM(c.c) AS numContacten, 'Minder dan vijf' AS label
                    FROM 
                    (SELECT COUNT(DISTINCT v.klant_id) as c
                        FROM `verslagen` `v`
                                 INNER JOIN locaties l ON v.locatie_id = l.id AND l.naam IN (:locatienamen)
                        WHERE v.`datum` BETWEEN :startdatum AND :einddatum
                        GROUP BY klant_id
                        HAVING SUM(v.aantalContactmomenten) < 5) AS c)
        UNION
        (SELECT SUM(d.c) AS numContacten, 'Vijf of meer' AS label
                    FROM 
                    (SELECT COUNT(DISTINCT v.klant_id) AS c
                        FROM `verslagen` `v`
                        INNER JOIN locaties l ON v.locatie_id = l.id AND l.naam IN (:locatienamen)
                        WHERE v.`datum` BETWEEN :startdatum AND :einddatum
                        GROUP BY klant_id
                        HAVING SUM(v.aantalContactmomenten) >= 5) AS d)";
        $conn = $this->entityManager->getConnection();
        $statement = $conn->prepare($query);
        //['locatienamen'=>$locatieNamen,'contactsoortid'=>1,'startdatum'=>$startdatum->format("Y-m-d"),'einddatum'=>$einddatum->format("Y-m-d")]
        $statement->bindValue("locatienamen",implode(", ",$locatieNamen));
        $statement->bindValue("contactsoortid",3);
        $statement->bindValue("startdatum",$startdatum,"datetime");
        $statement->bindValue("einddatum",$einddatum,"datetime");

        $result = $statement->executeQuery();

        return $result;

    }

    public function getTotalUniqueKlantenForLocaties($startdatum,$einddatum,$locaties): array
    {
        $builder = $this->repository->createQueryBuilder("verslagen");
        /**
         * SELECT COUNT(DISTINCT v.klant_id) AS totKlanten FROM verslagen v INNER JOIN locaties l ON v.locatie_id = l.id
        WHERE l.naam IN ("De Meeuw","Claverhuis","Gravestein","Postoost","Havelaar","\'t Blommetje","Tagrijn","Casa Jepie")
        AND (v.datum BETWEEN '2020-01-01 00:00:00' AND '2021-05-28 00:00:00')
         */
        $builder->select('COUNT(DISTINCT verslagen.klant) AS Klanten')
            ->leftJoin('verslagen.locatie', 'locatie')
            ->where('locatie.naam IN(:locaties)')
            ->andWhere('verslagen.datum BETWEEN :startdatum AND :einddatum')
            ->setParameters([
                'startdatum' => $startdatum,
                'einddatum' => $einddatum,
                'locaties' => $locaties
            ])
//            ->groupBy('locatie.naam')
            ;

        return $builder->getQuery()->getSingleResult();
    }

    public static function buildUniqueKlantenVoorLocatiesQuery($builder, $startdatum, $einddatum, $locaties)
    {
        $builder->addSelect('COUNT(DISTINCT verslagen.klant) AS aantalKlanten, COUNT(verslagen.id) AS aantalVerslagen, SUM(verslagen.aantalContactmomenten) AS aantalContactmomenten, locatie.naam AS locatienaam')
        ->leftJoin('verslagen.locatie', 'locatie')
        ->where('locatie.naam IN(:locaties)')
        ->andWhere('verslagen.datum BETWEEN :startdatum AND :einddatum')
        ->setParameters([
            'startdatum' => $startdatum,
            'einddatum' => $einddatum,
            'locaties' => $locaties
        ])
        ->groupBy('locatie.naam');

        return $builder;
    }
}
