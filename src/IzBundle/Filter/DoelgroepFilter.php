<?php

namespace IzBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class DoelgroepFilter implements FilterInterface
{
    public function applyTo(QueryBuilder $builder)
    {
    }
}
