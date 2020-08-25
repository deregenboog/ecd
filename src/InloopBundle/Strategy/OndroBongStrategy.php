<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class OndroBongStrategy extends VerblijfsstatusStrategy
{
    // @todo do not define database ID here
    private $locatieIds = [13,19];

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
        parent::buildQuery($builder);

        $builder->orWhere('laatsteIntake.ondroBongToegangVan <= DATE(NOW())');
    }
}
