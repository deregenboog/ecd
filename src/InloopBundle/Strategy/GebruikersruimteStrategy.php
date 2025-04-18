<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\RecenteRegistratie;

final class GebruikersruimteStrategy implements StrategyInterface
{
    /**
     * Deze strategie werkt alleen voor gebruikersruimtes.
     * Als iemand toegnag heeft tot een gebruikesrruimte, en voldoet aan de ovirge voorwaarden (niet langer dan 2 mnd weggeweest, of nieuw, en daarbij een intake van < 2 mnd
     * dan mag ie naar binnen bij alle gebruikersruimtes.
     */
    public function supports(Locatie $locatie): bool
    {
        if ($locatie->isGebruikersruimte()) {
            return true;
        }

        return false;
    }

    public function buildQuery(QueryBuilder $builder, Locatie $locatie)
    {
        $builder
            ->innerJoin('eersteIntake.gebruikersruimte', 'eersteIntakeGebruikersruimte')
            ->leftJoin('klant.registraties', 'registratie', 'WITH', 'registratie.locatie = :locatie_id')
            ->leftJoin(RecenteRegistratie::class, 'recent', 'WITH', 'recent.klant = klant AND recent.locatie = :locatie_id')
            ->leftJoin('recent.registratie', 'recenteRegistratie', 'WITH', 'DATE(recenteRegistratie.buiten) > :two_months_ago')
            ->orWhere('eersteIntake.toegangInloophuis = true')
            ->groupBy('klant.id')
            ->having('COUNT(recenteRegistratie) > 0') // recent geregistreerd op deze locatie
            ->orHaving('COUNT(registratie.id) = 0') // of nog nooit geregistreerd op deze locatie
            ->orHaving('MAX(laatsteIntake.intakedatum) > :two_months_ago') // of recent intake gehad
            ->setParameter('locatie_id', $locatie->getId())
            ->setParameter('two_months_ago', new \DateTime('-2 months'))
        ;
    }
}
