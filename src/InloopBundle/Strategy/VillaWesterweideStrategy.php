<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

final class VillaWesterweideStrategy implements StrategyInterface
{
    /**
     * This strategy looks if intake locatie  is linked to certain locaties where access is only granted to.
     * Ie. intake locatie AMOC West = toegang tot AMOC West and Nachtopvang DRG.
     * Intake locatie Villa Zaanstad = toegang tot Villa Zaanstad.
     */
    private const ACCESS_STRATEGY_NAME = 'villa_westerweide';

    private Locatie $locatie;

    private $intakeLocaties = [];

    public function __construct(array $accessStrategies)
    {
        $intakeLocaties = $accessStrategies[self::ACCESS_STRATEGY_NAME];
        $this->intakeLocaties = $intakeLocaties;
    }

    public function supports(Locatie $locatie): bool
    {
        return in_array($locatie->getNaam(), $this->intakeLocaties);
    }

    /**
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder)
    {
        $builder->orWhere('( eersteIntake.toegangInloophuis = true AND eersteIntakeLocatie.naam IN (:toegestaneLocatiesVoorIntakelocatie) )');
        $builder->setParameter('toegestaneLocatiesVoorIntakelocatie', $this->intakeLocaties);
    }
}
