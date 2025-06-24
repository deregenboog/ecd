<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

final class ToegangOverigStrategy implements StrategyInterface
{
    private $verblijsstatusNietRechthebbend;

    /** @var Locatie */
    private $locatie;

    /** @var array */
    private $intakeLocaties = [];

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(array $accessStrategies, string $amocVerblijfsstatus, EntityManagerInterface $em)
    {
        $this->em = $em;
        array_walk($accessStrategies, function ($v, $k) {
            $this->intakeLocaties = array_merge($this->intakeLocaties, $v);
        });

        $this->intakeLocaties = array_unique($this->intakeLocaties);
        $this->verblijsstatusNietRechthebbend = $amocVerblijfsstatus;
    }

    public function supports(Locatie $locatie): bool
    {
        if ($locatie->isGebruikersruimte()) {
            return false;
        }
        if(strpos($locatie->getNaam(), 'WLB') !== false){
            return false;
        }

        // Make sure the location is not specified in intake related rules.
        return !in_array($locatie->getNaam(), $this->intakeLocaties);
    }

    /**
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder, Locatie $locatie)
    {
        /*
         * Selecteer alle klanten, waarbij de eerste intake
         * - geen verblijfsstatus heeft
         * - of de veblijfsstatus niet gelijk is aan 'Niet rechthebben, (uit EU behalve NL)
         * - OF de verblijffstatus WEL gelijk is aan 'niet rechthebbend' EN de toegang 'overigen' al is ingegaan (in het verleden ligt)
         * waarbij Villa Zaanstad als intakelocatie een uitzondering heeft: die mogen hier niet komen.

         */

        $builder
            ->leftJoin('eersteIntake.verblijfsstatus', 'verblijfsstatus')
            ->orWhere(
                $builder->expr()->andX('eersteIntake.toegangInloophuis = true',
                    $builder->expr()->orX('eersteIntakeLocatie.naam != :villa_westerweide',
                        'eersteIntakeLocatie.naam IS NULL'),

                    $builder->expr()->orX(
                        'eersteIntake.verblijfsstatus IS NULL',
                        'verblijfsstatus.naam != :niet_rechthebbend',

                        $builder->expr()->andX(
                            'verblijfsstatus.naam = :niet_rechthebbend',
                            'eersteIntake.overigenToegangVan <= :today'
                        ),
                    ),
                )
            )
            ->setParameter('niet_rechthebbend', $this->verblijsstatusNietRechthebbend)
            ->setParameter('today', new \DateTime('today'))
            ->setParameter('villa_westerweide', 'Villa Westerweide')
        ;
    }
}
