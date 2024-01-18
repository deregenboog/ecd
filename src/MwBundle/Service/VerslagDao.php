<?php

namespace MwBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Exception\UserException;
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
        if(!$entity->getKlant()->getHuidigeMwStatus() instanceof Aanmelding)
        {
            throw new UserException("Kan geen verslagen toevoegen aan klanten zonder open MW dossier.");
        }
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



    public function countUniqueKlantenVoorLocaties(\DateTime $startdatum, \DateTime $einddatum, $locatieNamen,$actieveKlanten=[]) {


        $builder = $this->repository->createQueryBuilder('verslagen');

        $builder = self::buildUniqueKlantenVoorLocatiesQuery($builder,$startdatum,$einddatum,$locatieNamen,$actieveKlanten);

//            ->setParameter("totKlant",5)
            ;

        $sql = SqlExtractor::getFullSQL($builder->getQuery());

        return $builder->getQuery()->getResult();

    }



    public function countUniqueKlantenEnGezinnenVoorLocaties(\DateTime $startdatum, \DateTime $einddatum, $locatieNamen,$actieveKlanten=[]) {


        $builder = $this->repository->createQueryBuilder('verslagen');

        $builder = self::buildUniqueKlantenEnGezinnenVoorLocatiesQuery($builder,$startdatum,$einddatum,$locatieNamen,$actieveKlanten);

//            ->setParameter("totKlant",5)
        ;

//        $sql = SqlExtractor::getFullSQL($builder->getQuery());

        return $builder->getQuery()->getResult();

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

        $statement->bindValue("locatienamen",implode(", ",$locatieNamen));
        $statement->bindValue("startdatum",$startdatum,"datetime");
        $statement->bindValue("einddatum",$einddatum,"datetime");

        $result = $statement->executeQuery();

        return $result;

    }

    public function countContactmomentenPerMedewerker($startdatum, $einddatum, $actieveKlanten)
    {
        $builder = $this->repository->createQueryBuilder('verslagen');

        $builder->addSelect('SUM(verslagen.aantalContactmomenten) AS aantal, COUNT(verslagen.id) AS aantalVerslagen, CONCAT_WS(\' \',medewerker.voornaam, medewerker.tussenvoegsel, medewerker.achternaam) AS naam')
            ->leftJoin('verslagen.medewerker', 'medewerker')
            ->andWhere('verslagen.datum BETWEEN :startdatum AND :einddatum')

            ->groupBy('naam')
            ->orderBy('medewerker.achternaam','ASC')

            ->setParameter("startdatum",$startdatum)
            ->setParameter("einddatum",$einddatum)

        ;
        if(count($actieveKlanten) > 0)
        {
            $builder
                ->andWhere('verslagen.klant IN (:actieveKlanten)')
                ->setParameter("actieveKlanten",$actieveKlanten)
            ;
        }

        return $builder->getQuery()->getResult();
    }


    public function getTotalUniqueKlantenForLocaties($startdatum,$einddatum,$locaties, $actieveKlanten=[]): array
    {
        $builder = $this->repository->createQueryBuilder('verslagen');

        $builder = self::buildTotalUniqueKlantenEnGezinnenVoorLocatiesQuery($builder,$startdatum,$einddatum,$locaties,$actieveKlanten);

//            ->setParameter("totKlant",5)
        ;

//        $sql = SqlExtractor::getFullSQL($builder->getQuery());

        return $builder->getQuery()->getSingleResult();
    }

    public function getTotalUniqueKlantenEnGezinnenForLocaties($startdatum,$einddatum,$locaties, $actieveKlanten=[]): array
    {
        $builder = $this->repository->createQueryBuilder('verslagen');

        $builder = self::buildTotalUniqueKlantenEnGezinnenVoorLocatiesQuery($builder,$startdatum,$einddatum,$locaties,$actieveKlanten);

//            ->setParameter("totKlant",5)
        ;

//        $sql = SqlExtractor::getFullSQL($builder->getQuery());

        return $builder->getQuery()->getSingleResult();
    }

    public static function buildUniqueKlantenVoorLocatiesQuery($builder, $startdatum, $einddatum, $locaties, $actieveKlanten)
    {
        $builder->addSelect('COUNT(DISTINCT verslagen.klant) AS aantalKlanten, COUNT(verslagen.id) AS aantalVerslagen, SUM(verslagen.aantalContactmomenten) AS aantalContactmomenten, locatie.naam AS locatienaam')
            ->addSelect('SUM(CASE WHEN (verslagen.type = 1) THEN 1 ELSE 0 END) AS aantalMw')
            ->addSelect('SUM(CASE WHEN (verslagen.type = 2) THEN 1 ELSE 0 END) AS aantalInloop')
            ->leftJoin('verslagen.locatie', 'locatie')
            ->where('locatie.naam IN(:locaties)')
            ->andWhere('verslagen.datum BETWEEN :startdatum AND :einddatum')

            ->groupBy('locatie.naam')
            ->setParameter("startdatum",$startdatum)
            ->setParameter("einddatum",$einddatum)
            ->setParameter("locaties",$locaties)

        ;
        if(count($actieveKlanten) > 0)
        {
            $builder
                ->andWhere('verslagen.klant IN (:actieveKlanten)')
                ->setParameter("actieveKlanten",$actieveKlanten)
            ;
        }

        return $builder;
    }

    public static function buildUniqueKlantenEnGezinnenVoorLocatiesQuery($builder, $startdatum, $einddatum, $locaties, $actieveKlanten)
    {
        $builder->addSelect('COUNT(DISTINCT verslagen.klant) AS aantalKlanten, COUNT(verslagen.id) AS aantalVerslagen, SUM(verslagen.aantalContactmomenten) AS aantalContactmomenten, locatie.naam AS locatienaam')
            ->addSelect('SUM(CASE WHEN (info.isGezin = 1) THEN 1 ELSE 0 END) AS aantalGezinnen')
            ->addSelect('SUM(CASE WHEN (verslagen.type = 1) THEN 1 ELSE 0 END) AS aantalMw')
            ->addSelect('SUM(CASE WHEN (verslagen.type = 2) THEN 1 ELSE 0 END) AS aantalInloop')
            ->leftJoin('verslagen.locatie', 'locatie')
            ->innerJoin('verslagen.klant','klant')
            ->innerJoin('klant.info','info')
            ->where('locatie.naam IN(:locaties)')
            ->andWhere('verslagen.datum BETWEEN :startdatum AND :einddatum')

            ->groupBy('locatie.naam')
            ->setParameter("startdatum",$startdatum)
            ->setParameter("einddatum",$einddatum)
            ->setParameter("locaties",$locaties)

        ;
        if(count($actieveKlanten) > 0)
        {
            $builder
                ->andWhere('verslagen.klant IN (:actieveKlanten)')
                ->setParameter("actieveKlanten",$actieveKlanten)
            ;
        }

        return $builder;
    }


    public static function buildTotalUniqueKlantenEnGezinnenVoorLocatiesQuery($builder, $startdatum, $einddatum, $locaties, $actieveKlanten)
    {
        $builder->addSelect('COUNT(DISTINCT verslagen.klant) AS aantalKlanten, COUNT(verslagen.id) AS aantalVerslagen, SUM(verslagen.aantalContactmomenten) AS aantalContactmomenten, locatie.naam AS locatienaam')
            ->addSelect('SUM(CASE WHEN (info.isGezin = 1) THEN 1 ELSE 0 END) AS aantalGezinnen')
            ->addSelect('SUM(CASE WHEN (verslagen.type = 1) THEN 1 ELSE 0 END) AS aantalMw')
            ->addSelect('SUM(CASE WHEN (verslagen.type = 2) THEN 1 ELSE 0 END) AS aantalInloop')
            ->leftJoin('verslagen.locatie', 'locatie')
            ->innerJoin('verslagen.klant','klant')
            ->innerJoin('klant.info','info')
            ->where('locatie.naam IN(:locaties)')
            ->andWhere('verslagen.datum BETWEEN :startdatum AND :einddatum')

//            ->groupBy('locatie.naam')
            ->setParameter("startdatum",$startdatum)
            ->setParameter("einddatum",$einddatum)
            ->setParameter("locaties",$locaties)

        ;
        if(count($actieveKlanten) > 0)
        {
            $builder
                ->andWhere('verslagen.klant IN (:actieveKlanten)')
                ->setParameter("actieveKlanten",$actieveKlanten)
            ;
        }

        return $builder;
    }
}
