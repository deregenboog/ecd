<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

final class AmocWestStrategy implements StrategyInterface
{
    /**
     * This strategy looks if intake locatie  is linked to certain locaties where access is only granted to.
     * Ie. intake locatie AMOC West = toegang tot AMOC West and Nachtopvang DRG.
     * Intake locatie Villa Zaanstad = toegang tot Villa Zaanstad.
     */
    private const ACCESS_STRATEGY_NAME = 'amoc_west';

    private Locatie $locatie;

    private array $intakeLocaties = [];

    public function __construct(array $accessStrategies)
    {
        $this->intakeLocaties = $accessStrategies[self::ACCESS_STRATEGY_NAME];
    }

    public function supports(Locatie $locatie): bool
    {
        return in_array($locatie->getNaam(), $this->intakeLocaties);
    }

    /**
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder, Locatie $locatie)
    {
        $builder
            ->orWhere('(eaf.toegangInloophuis = true AND eersteIntakeLocatie.naam IN (:toegestaneLocatiesVoorIntakelocatie))')
            ->setParameter('toegestaneLocatiesVoorIntakelocatie', $this->intakeLocaties);
    }
}
