<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class DienstverlenerFilter implements FilterInterface
{
    public $alias = 'dienstverlener';

    /**
     * @var int
     */
    public $id;

    /**
     * @var bool
     */
    public $rijbewijs;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere("{$this->alias}.id = :{$this->alias}_id")
                ->setParameter("{$this->alias}_id", $this->id)
            ;
        }

        if ($this->rijbewijs) {
            $builder->andWhere("{$this->alias}.rijbewijs = true");
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
