<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Locatie;

interface StrategyInterface
{
    public function supports(Locatie $locatie);

    public function buildQuery(QueryBuilder $builder);
}
