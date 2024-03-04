<?php

namespace OekraineBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Locatie;

class VerblijfsstatusStrategy implements StrategyInterface
{
    // @todo do not define database ID here
    /*
     * @deprecated
     */
    private $verblijfsstatusIdNietRechthebbend = 7;

    private $verblijsstatusNietRechthebbend = "Europees Burger (Niet Nederlands)";

    public function supports(Locatie $locatie): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @see \OekraineBundle\Strategy\StrategyInterface::buildQuery()
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
            ->andWhere($builder->expr()->orX(
                'eersteIntake.verblijfsstatus IS NULL',
                    'verblijfsstatus.naam != :niet_rechthebbend',
//                    $builder->expr()->andX(
//                        'eersteIntake.verblijfsstatus = :niet_rechthebbend_id',
//    //                    'laatsteIntake.overigenToegangVan <= :today',
////                        "eersteIntake.intakedatum < '2017-06-01'"
//                    ),//Deprecated since oktober 2020.
                    $builder->expr()->andX(
                        'verblijfsstatus.naam = :niet_rechthebbend',
                        'eersteIntake.overigenToegangVan <= :today'
//                        ,"eersteIntake.intakedatum >= '2017-06-01'", // niet meer relevant in overleg janneke 2020-09
//                        "eersteIntake.intakedatum < :six_months_ago" //Kijkt niet meer naar intakedatum sinds 2020-10.
                    )
            ))
            ->setParameters([
                'niet_rechthebbend' => $this->verblijsstatusNietRechthebbend,
                'today' => new \DateTime('today'),
//                'three_months_ago' => new \DateTime('-3 months'),
//                'six_months_ago' => new \DateTime('-6 months'),
            ])
        ;
    }
}
