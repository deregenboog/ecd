<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class HsVrijwilligerFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('hsVrijwilliger.id = :hs_vrijwilliger_id')
                ->setParameter('hs_vrijwilliger_id', $this->id)
            ;
        }

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }
    }
}
