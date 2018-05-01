<?php

namespace AppBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Form\Model\AppDateRangeModel;

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
     * @var string
     */
    public $requestModule;

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

        if ($this->requestModule) {
            $builder
                ->andWhere("{$alias}.requestModule = :{$alias}_request_module")
                ->setParameter("{$alias}_request_module", $this->requestModule)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
