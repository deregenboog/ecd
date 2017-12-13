<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;

class HulpvraagsoortFilter implements FilterInterface
{
    public function applyTo(QueryBuilder $builder)
    {
    }
}
