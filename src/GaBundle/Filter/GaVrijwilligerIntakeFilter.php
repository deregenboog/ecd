<?php

namespace GaBundle\Filter;

use AppBundle\Filter\VrijwilligerFilter;
use Doctrine\ORM\QueryBuilder;

class GaVrijwilligerIntakeFilter extends GaIntakeFilter
{
    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    public function applyTo(QueryBuilder $builder)
    {
        parent::applyTo($builder);

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }
    }
}
