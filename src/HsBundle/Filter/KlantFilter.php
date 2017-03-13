<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class KlantFilter implements FilterInterface
{
    public $alias = 'klant';

    /**
     * @var int
     */
    public $id;

    /**
     * @var bool
     */
    public $openstaand;

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

        if ($this->openstaand) {
            $builder
                ->innerJoin("{$this->alias}.klussen", 'klus')
                ->innerJoin('klus.facturen', 'factuur')
                ->innerJoin('factuur.betalingen', 'betaling')
                ->having('(SUM(factuur.bedrag) - SUM(betaling.bedrag)) > 0')
            ;
        }

        if ($this->klant) {
            $this->klant->alias = 'basisklant';
            $this->klant->applyTo($builder);
        }
    }
}
