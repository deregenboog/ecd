<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;

class DoelgroepFilter implements FilterInterface
{
    public function applyTo(QueryBuilder $builder)
    {
    }
}
