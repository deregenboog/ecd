<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\RecenteRegistratie;

class GebruikersruimteStrategy implements StrategyInterface
{
    private $locatie;

    /**
     * Deze strategie werkt alleen voor gebruikersruimtes.
     * Als iemand toegnag heeft tot een gebruikesrruimte, en voldoet aan de ovirge voorwaarden (niet langer dan 2 mnd weggeweest, of nieuw, en daarbij een intake van < 2 mnd
     * dan mag ie naar binnen.
     *
     * @param Locatie $locatie
     * @return bool
     */
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
            ->innerJoin('eersteIntake.gebruikersruimte', 'eersteIntakeGebruikersruimte')
            ->leftJoin('klant.registraties', 'registratie', 'WITH', 'registratie.locatie = :locatie_id')
            ->leftJoin(RecenteRegistratie::class, 'recent', 'WITH', 'recent.klant = klant AND recent.locatie = :locatie_id')
            ->leftJoin('recent.registratie', 'recenteRegistratie', 'WITH', 'DATE(recenteRegistratie.buiten) > :two_months_ago')
            ->orWhere('( eersteIntake.toegangInloophuis = true AND eersteIntakeGebruikersruimte.id = :locatie_id )')
            ->groupBy('klant.id')
            ->having('COUNT(recenteRegistratie) > 0') // recent geregistreerd op deze locatie
            ->orHaving('COUNT(registratie.id) = 0') // of nog nooit geregistreerd op deze locatie
            ->orHaving('MAX(laatsteIntake.intakedatum) > :two_months_ago') // of recent intake gehad
            ->setParameter('locatie_id',$this->locatie->getId() )
            ->setParameter('two_months_ago', new \DateTime('-2 months') )
        ;
    }
}
