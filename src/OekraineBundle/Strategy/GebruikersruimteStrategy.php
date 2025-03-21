<?php

namespace OekraineBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Entity\RecenteRegistratie;

class GebruikersruimteStrategy implements StrategyInterface
{
    private $locatie;

    public function supports(Locatie $locatie): bool
    {
        //        if ($locatie->isGebruikersruimte()) {
        //            $this->locatie = $locatie;
        //
        //            return true;
        //        }

        return false;
    }

    public function buildQuery(QueryBuilder $builder)
    {
        $builder
            ->innerJoin('eersteIntake.gebruikersruimte', 'eersteIntakeGebruikersruimte')
            ->leftJoin('klant.registraties', 'registratie', 'WITH', 'registratie.locatie = :locatie_id')
            ->leftJoin(RecenteRegistratie::class, 'recent', 'WITH', 'recent.klant = klant AND recent.locatie = :locatie_id')
            ->leftJoin('recent.registratie', 'recenteRegistratie', 'WITH', 'DATE(recenteRegistratie.buiten) > :two_months_ago')
            ->andWhere('eersteIntakeGebruikersruimte.id = :locatie_id')
            ->groupBy('klant.id')
            ->having('COUNT(recenteRegistratie) > 0') // recent geregistreerd op deze locatie
            ->orHaving('COUNT(registratie.id) = 0') // of nog nooit geregistreerd op deze locatie
            ->orHaving('MAX(laatsteIntake.intakedatum) > :two_months_ago') // of recent intake gehad
            ->setParameters([
                    'locatie_id' => $this->locatie->getId(),
                    'two_months_ago' => new \DateTime('-2 months'),
                ]
            )
        ;
    }
}
