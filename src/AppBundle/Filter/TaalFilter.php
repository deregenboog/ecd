<?php

namespace AppBundle\Filter;

use Doctrine\ORM\QueryBuilder;

class TaalFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $naam;

    public function applyTo(QueryBuilder $builder, $alias = 'taal')
    {
        if ($this->naam) {
            $builder
                ->andWhere("{$alias}.naam LIKE :{$alias}_naam")
                ->setParameter("{$alias}_naam", "%{$this->naam}%")
            ;
        }
    }
}
