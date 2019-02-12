<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\RecenteRegistratie;

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
            ->innerJoin(RecenteRegistratie::class, 'recenteRegistratie', 'WITH', 'recenteRegistratie.klant = klant AND recenteRegistratie.locatie = :locatie_id')
            ->innerJoin('recenteRegistratie.registratie', 'registratie', 'WITH', 'DATE(registratie.buiten) > :two_weeks_ago')
            ->andWhere('laatsteIntakeGebruikersruimte.id = :locatie_id')
            ->setParameter('locatie_id', $this->locatie->getId())
            ->setParameter('two_weeks_ago', new \DateTime('-2 weeks'))
        ;
    }
}
