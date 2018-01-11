<?php

namespace OekBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;

class VrijwilligerFilter implements FilterInterface
{
    public $alias = 'vrijwilliger';

    /**
     * @var AppBundle\Filter\VrijwilligerFilter
     */
    public $vrijwilliger;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }
    }
}
