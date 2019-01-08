<?php

namespace HsBundle\Filter;

use Doctrine\ORM\QueryBuilder;

class VrijwilligerFilter extends ArbeiderFilter
{
    public $alias = 'vrijwilliger';

    /**
     * @var \AppBundle\Filter\VrijwilligerFilter
     */
    public $vrijwilliger;

    public function applyTo(QueryBuilder $builder)
    {
        parent::applyTo($builder);

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder, 'basisvrijwilliger');
        }
    }
}
