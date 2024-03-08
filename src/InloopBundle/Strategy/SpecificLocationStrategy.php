<?php

namespace InloopBundle\Strategy;

use AppBundle\Doctrine\SqlExtractor;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

final class SpecificLocationStrategy implements StrategyInterface
{
    public function supports(Locatie $locatie): bool
    {
        return true;
    }

    /**
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder, Locatie $locatie)
    {
        /*
         * Selecteer alle klanten die specifieke locaties hebben genoemd in hun toegangsprofiel.
         */
        $builder
            ->leftJoin('eersteIntake.specifiekeLocaties', 'specifiekeLocaties')
            ->orWhere('(eersteIntake.toegangInloophuis = true AND :locatie IN (specifiekeLocaties) )')
            ->setParameter('locatie', $locatie)
        ;
    }
}
