<?php

namespace AppBundle\Filter;

use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
    public function applyTo(QueryBuilder $builder);
}
