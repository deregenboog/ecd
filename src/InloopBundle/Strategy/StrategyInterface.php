<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

interface StrategyInterface
{
    public function supports(Locatie $locatie): bool;

    public function buildQuery(QueryBuilder $builder, Locatie $locatie);
}
