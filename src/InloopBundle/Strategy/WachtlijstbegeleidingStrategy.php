<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\RecenteRegistratie;
use InloopBundle\Strategy\StrategyInterface;

final class WachtlijstbegeleidingStrategy implements StrategyInterface
{
    /**
     * Deze strategie werkt alleen voor gebruikersruimtes.
     * Als iemand toegnag heeft tot een gebruikesrruimte, en voldoet aan de ovirge voorwaarden (niet langer dan 2 mnd weggeweest, of nieuw, en daarbij een intake van < 2 mnd
     * dan mag ie naar binnen bij alle gebruikersruimtes.
     */
    public function supports(Locatie $locatie): bool
    {
        if(strpos($locatie->getNaam(), 'WLB') !== false){
            return true;
        }
        return false;
    }

    public function buildQuery(QueryBuilder $builder, Locatie $locatie)
    {
        $builder
            ->AndWhere('eersteIntake.toegangInloophuis = true AND eersteIntake.beschikkingWachtlijstbegeleiding = true')
//            ->groupBy('klant.id')
            ->setParameter('locatie_id', $locatie->getId())
        ;
    }
}
