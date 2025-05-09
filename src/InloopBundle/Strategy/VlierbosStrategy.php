<?php

namespace InloopBundle\Strategy;

use AppBundle\Doctrine\SqlExtractor;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Strategy\StrategyInterface;

final class VlierbosStrategy implements StrategyInterface
{
    /**
     * This strategy looks if intake locatie  is linked to certain locaties where access is only granted to.
     * Ie. intake locatie AMOC West = toegang tot AMOC West and Nachtopvang DRG.
     * Intake locatie Villa Zaanstad = toegang tot Villa Zaanstad.
     */
    private const ACCESS_STRATEGY_NAME = 'vlierbos';

    private Locatie $locatie;

    private array $intakeLocaties = [];

    private $verblijsstatusNietRechthebbend;


    public function __construct(array $accessStrategies, string $amocVerblijfsstatus)
    {
        $this->intakeLocaties = $accessStrategies[self::ACCESS_STRATEGY_NAME];
        $this->verblijsstatusNietRechthebbend = $amocVerblijfsstatus;
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
//        $builder
//            ->orWhere('(eersteIntake.toegangInloophuis = true AND eersteIntakeLocatie.naam IN (:toegestaneLocatiesVoorIntakelocatie))')
//            ->setParameter('toegestaneLocatiesVoorIntakelocatie', $this->intakeLocaties);

        $builder
            ->leftJoin('eersteIntake.verblijfsstatus', 'verblijfsstatus')
            ->orWhere(
                $builder->expr()->andX('eersteIntake.toegangInloophuis = true',
               'eersteIntakeLocatie.naam IN (:toegestaneLocatiesVoorIntakelocatie)'

                ),
                $builder->expr()->orX(
                    'eersteIntake.verblijfsstatus IS NULL',
                    'verblijfsstatus.naam != :niet_rechthebbend',
                ),
            )
            ->setParameter('niet_rechthebbend', $this->verblijsstatusNietRechthebbend)
            ->setParameter('toegestaneLocatiesVoorIntakelocatie', $this->intakeLocaties)
        ;

        ;

//        $sql = SqlExtractor::getFullSQL($builder->getQuery());
    }
}
