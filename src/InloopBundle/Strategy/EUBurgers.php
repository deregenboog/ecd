<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

final class EUBurgers implements StrategyInterface
{
    private const ACCESS_STRATEGY_NAME = 'eu_burgers';

    private $locaties = [];

    private $verblijfsstatus = '';

    /**
     *Alle klanten met verblijfssstatus EU Burger (niet NL) mogen in de locaties zoals genoemd in de parameters.
     *
     */
    public function __construct(array $accessStrategies, $amocVerblijfsstatus)
    {
        $this->locaties = $accessStrategies[self::ACCESS_STRATEGY_NAME];
        $this->verblijfsstatus = $amocVerblijfsstatus;

    }

    public function supports(Locatie $locatie): bool
    {
        return in_array($locatie->getNaam(), $this->locaties);
    }

    /**
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder, Locatie $locatie)
    {
        $builder->leftJoin('eersteIntake.verblijfsstatus', 'verblijfsstatus');
        $builder->orWhere("( eersteIntake.toegangInloophuis = true AND verblijfsstatus.naam = :verblijfsstatus)");
        $builder->setParameter('verblijfsstatus', $this->verblijfsstatus);
    }
}
