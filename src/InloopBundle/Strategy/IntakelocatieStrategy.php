<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class IntakelocatieStrategy implements StrategyInterface
{

    /**
     * This strategy looks if intake locatie  is linked to certain locaties where access is only granted to.
     * Ie. intake locatie AMOC or AMOC West = toegang tot AMOC.
     * Intake locatie Villa Zaanstad = toegang tot Villa Zaanstad.
     *
     *
     */

    private Locatie $locatie;

    private $intakeLocaties = [];

    private $permittedLocaties = [];

    /**
     * @param array $intakeLocaties
     */
    public function __construct(array $intakeLocaties)
    {
        $this->intakeLocaties = $intakeLocaties;
    }


    public function supports(Locatie $locatie)
    {
        $this->locatie = $locatie;

        foreach($this->intakeLocaties as $intakeLocatie)
        {
            $naam = $intakeLocatie["name"];
            $lNaam = $locatie->getNaam();
            if($intakeLocatie["name"] == $locatie->getNaam()) {
                $this->permittedLocaties = $intakeLocatie["locaties"];
                return true;
            }
        }
        return false;


    }

    /**
     * {@inheritdoc}
     *
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder)
    {
        $builder->orWhere('( eersteIntakeLocatie.naam IN (:toegestaneLocatiesVoorIntakelocatie) AND eersteIntake.toegangIntakelocatie = true)');
        $builder->setParameter('toegestaneLocatiesVoorIntakelocatie', $this->permittedLocaties);

    }
}
