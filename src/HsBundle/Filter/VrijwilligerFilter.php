<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class VrijwilligerFilter implements FilterInterface
{
    public $alias = 'vrijwilliger';

    /**
     * @var int
     */
    public $id;

    /**
     * @var bool
     */
    public $dragend;

    /**
     * @var bool
     */
    public $rijbewijs;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere("{$this->alias}.id = :{$this->alias}_id")
                ->setParameter("{$this->alias}_id", $this->id)
            ;
        }

        if ($this->dragend) {
            $builder->andWhere("{$this->alias}.dragend = true");
        }

        if ($this->rijbewijs) {
            $builder->andWhere("{$this->alias}.rijbewijs = true");
        }

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }
    }
}
