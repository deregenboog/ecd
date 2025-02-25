<?php

namespace AppBundle\Filter;

use Doctrine\ORM\QueryBuilder;

class NationaliteitFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $naam;

    public function applyTo(QueryBuilder $builder, $alias = 'nationaliteit')
    {
        if ($this->naam) {
            $builder
                ->andWhere("{$alias}.naam LIKE :{$alias}_naam")
                ->setParameter("{$alias}_naam", "%{$this->naam}%")
            ;
        }
    }
}
