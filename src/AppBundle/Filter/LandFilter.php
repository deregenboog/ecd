<?php

namespace AppBundle\Filter;

use Doctrine\ORM\QueryBuilder;

class LandFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $naam;

    public function applyTo(QueryBuilder $builder, $alias = 'land')
    {
        if ($this->naam) {
            $builder
                ->andWhere("{$alias}.land LIKE :{$alias}_naam")
                ->setParameter("{$alias}_naam", "%{$this->naam}%")
            ;
        }
    }
}
