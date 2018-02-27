<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class GebruikersruimteStrategy implements StrategyInterface
{
    private $locatie;

    public function supports(Locatie $locatie)
    {
        if ($locatie->isGebruikersruimte()) {
            $this->locatie = $locatie;

            return true;
        }

        return false;
    }

    public function buildQuery(QueryBuilder $builder)
    {
        $builder
            ->innerJoin('laatsteIntake.gebruikersruimte', 'laatsteIntakeGebruikersruimte')
            ->andWhere('laatsteIntakeGebruikersruimte.id = :locatie_id')
            ->setParameter('locatie_id', $this->locatie->getId())
        ;
    }
}
