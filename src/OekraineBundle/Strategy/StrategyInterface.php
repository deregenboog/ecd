<?php

namespace OekraineBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Locatie;

interface StrategyInterface
{
    public function supports(Locatie $locatie);

    public function buildQuery(QueryBuilder $builder);
}
