<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class AmocStrategy implements StrategyInterface
{
    // @todo do not define database ID here
    private $amocId = 5;

    public function supports(Locatie $locatie)
    {
        return $this->amocId == $locatie->getId();
    }

    /**
     * {@inheritdoc}
     *
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder)
    {
        $builder->andWhere('laatsteIntake.amocToegangTot IS NULL OR laatsteIntake.amocToegangTot >= DATE(NOW())');
    }
}
