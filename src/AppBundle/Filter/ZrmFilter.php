<?php

namespace AppBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;

class ZrmFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var AppDateRangeModel
     */
    public $created;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder, $alias = 'zrm')
    {
        if ($this->id) {
            $builder
                ->andWhere("{$alias}.id = :{$alias}_id")
                ->setParameter("{$alias}_id", $this->id)
            ;
        }

        if ($this->created) {
            if ($this->created->getStart()) {
                $builder
                    ->andWhere("{$alias}.created >= :{$alias}_created_van")
                    ->setParameter("{$alias}_created_van", $this->created->getStart())
                ;
            }
            if ($this->created->getEnd()) {
                $builder
                    ->andWhere("{$alias}.created <= :{$alias}_created_tot")
                    ->setParameter("{$alias}_created_tot", $this->created->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
