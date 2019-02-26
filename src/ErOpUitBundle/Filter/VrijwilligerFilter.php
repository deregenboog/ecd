<?php

namespace ErOpUitBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;

class VrijwilligerFilter implements FilterInterface
{
    public $alias = 'vrijwilliger';

    /**
     * @var \AppBundle\Filter\VrijwilligerFilter
     */
    public $vrijwilliger;

    /**
     * @var AppDateRangeModel
     */
    public $inschrijfdatum;

    /**
     * @var AppDateRangeModel
     */
    public $uitschrijfdatum;

    /**
     * @var bool
     */
    public $actief = true;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder, 'appVrijwilliger');
        }

        if ($this->inschrijfdatum) {
            if ($this->inschrijfdatum->getStart()) {
                $builder
                    ->andWhere("{$this->alias}.inschrijfdatum >= :{$this->alias}_inschrijfdatum_start")
                    ->setParameter("{$this->alias}_inschrijfdatum_start", $this->inschrijfdatum->getStart())
                ;
            }
            if ($this->inschrijfdatum->getEnd()) {
                $builder
                    ->andWhere("{$this->alias}.inschrijfdatum <= :{$this->alias}_inschrijfdatum_end")
                    ->setParameter("{$this->alias}_inschrijfdatum_end", $this->inschrijfdatum->getEnd())
                ;
            }
        }

        if ($this->uitschrijfdatum) {
            if ($this->uitschrijfdatum->getStart()) {
                $builder
                    ->andWhere("{$this->alias}.uitschrijfdatum >= :{$this->alias}_uitschrijfdatum_start")
                    ->setParameter("{$this->alias}_uitschrijfdatum_start", $this->uitschrijfdatum->getStart())
                ;
            }
            if ($this->uitschrijfdatum->getEnd()) {
                $builder
                    ->andWhere("{$this->alias}.uitschrijfdatum <= :{$this->alias}_uitschrijfdatum_end")
                    ->setParameter("{$this->alias}_uitschrijfdatum_end", $this->uitschrijfdatum->getEnd())
                ;
            }
        }

        if ($this->actief) {
            $builder
                ->andWhere("{$this->alias}.inschrijfdatum <= :today")
                ->andWhere("{$this->alias}.uitschrijfdatum > :today OR {$this->alias}.uitschrijfdatum IS NULL")
                ->setParameter('today', new \DateTime('today'))
            ;
        }
    }
}
