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
        $builder->addSelect('COUNT(DISTINCT verslagen.klant) AS aantal, COUNT(verslagen.id) AS aantalVerslagen, locatie.naam AS locatienaam')
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
