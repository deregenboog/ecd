<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class OndroBongStrategy implements StrategyInterface
{
    // @todo do not define database ID here
    private $locatieIds = [13];

    public function supports(Locatie $locatie)
    {
        return in_array($locatie->getId(), $this->locatieIds);
    }

    /**
     * {@inheritdoc}
     *
     * @see https://github.com/deregenboog/ecd/issues/749
     */
    public function buildQuery(QueryBuilder $builder)
    {
        $builder->andWhere('laatsteIntake.ondroBongToegangVan <= DATE(NOW())');
    }
}
