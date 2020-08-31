<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class VerblijfsstatusStrategy implements StrategyInterface
{
    // @todo do not define database ID here
    private $verblijfsstatusIdNietRechthebbend = 7;

    public function supports(Locatie $locatie)
    {
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
        $builder
            ->andWhere($builder->expr()->orX(
                'laatsteIntake.verblijfsstatus IS NULL',
                    'laatsteIntake.verblijfsstatus != :niet_rechthebbend_id',
                    $builder->expr()->andX(
                        'laatsteIntake.verblijfsstatus = :niet_rechthebbend_id',
    //                    'laatsteIntake.overigenToegangVan <= :today',
                        "eersteIntake.intakedatum < '2017-06-01'"
                    ),
                    $builder->expr()->andX(
                        'laatsteIntake.verblijfsstatus = :niet_rechthebbend_id',
    //                    'laatsteIntake.overigenToegangVan <= :today', //Even weghalen. In overleg met Janneke wordt dit verhaal voorlopig geskipt.
                        "eersteIntake.intakedatum >= '2017-06-01'",
                        "eersteIntake.intakedatum < :six_months_ago"
                    )
            ))
            ->setParameters([
                'niet_rechthebbend_id' => $this->verblijfsstatusIdNietRechthebbend,
//                'today' => new \DateTime('today'),
//                'three_months_ago' => new \DateTime('-3 months'),
                'six_months_ago' => new \DateTime('-6 months'),
            ])
        ;
    }
}
