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
                ->andWhere('hsVrijwilliger.id = :hs_vrijwilliger_id')
                ->setParameter('hs_vrijwilliger_id', $this->id)
            ;
        }

        if ($this->dragend) {
            $builder->andWhere('hsVrijwilliger.dragend = true');
        }

        if ($this->rijbewijs) {
            $builder->andWhere('hsVrijwilliger.rijbewijs = true');
        }

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }
    }
}
