<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Verslag;

class VerslagDao extends AbstractDao implements VerslagDaoInterface
{
    protected $class = Verslag::class;

    protected $alias = 'verslag';

    public function create(Verslag $entity)
    {
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
//        $sql = $this->getFullSQL($builder->getQuery());

        return $builder->getQuery();

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
