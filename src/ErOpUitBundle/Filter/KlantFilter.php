<?php

namespace ErOpUitBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;

class KlantFilter implements FilterInterface
{
    const STATUS_ACTIVE = 'Actief';
    const STATUS_NON_ACTIVE = 'Niet actief';

    public $alias = 'klant';

    /**
     * @var AppKlantFilter
     */
    public $klant;

    /**
     * @var AppDateRangeModel
     */
    public $inschrijfdatum;

    /**
     * @var AppDateRangeModel
     */
    public $uitschrijfdatum;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->klant) {
            $this->klant->applyTo($builder, 'appKlant');
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
    }
}
