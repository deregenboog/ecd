<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class ToegangOverigStrategy implements StrategyInterface
{
    // @todo do not define database ID here
    /*
     * @deprecated
     */
    private $verblijfsstatusIdNietRechthebbend = 7;

    private $verblijsstatusNietRechthebbend = "Niet rechthebbend (uit EU, behalve Nederland)";

    /** @var Locatie */
    private $locatie;

    /** @var array  */
    private $intakeLocaties = [];

    /**
     * @param array $intake_locaties
     */
    public function __construct(array $intake_locaties)
    {
        $this->intakeLocaties = $intake_locaties;
    }


    public function supports(Locatie $locatie)
    {
        $this->locatie = $locatie;

        if($locatie->isGebruikersruimte()) return false;

        //and make sure the location is not specified in intake related rules.
        foreach($this->intakeLocaties as $intakeLocatie)
        {
            if($intakeLocatie["name"] == $locatie->getNaam()) {
                $this->permittedLocaties = $intakeLocatie["locaties"];
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder)
    {
        /**
         * Selecteer alle klanten, waarbij de eerste intake
         * - geen verblijfsstatus heeft
         * - of de veblijfsstatus niet gelijk is aan 'Niet rechthebben, (uit EU behalve NL)
         * - OF de verblijffstatus WEL gelijk is aan 'niet rechthebbend' EN de toegang 'overigen' al is ingegaan (in het verleden ligt)
         *
         */
        $builder
            ->leftJoin("eersteIntake.verblijfsstatus","verblijfsstatus")
            ->orWhere($builder->expr()->orX(
                'eersteIntake.verblijfsstatus IS NULL',
                    'verblijfsstatus.naam != :niet_rechthebbend',

                    $builder->expr()->andX(
                        'verblijfsstatus.naam = :niet_rechthebbend',
                        'eersteIntake.overigenToegangVan <= :today'
                    )
            ))

            ->setParameter('niet_rechthebbend', $this->verblijsstatusNietRechthebbend)
            ->setParameter('today', new \DateTime('today'))
        ;
    }
}
