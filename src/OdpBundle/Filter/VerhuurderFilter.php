<?php

namespace OdpBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter;

class VerhuurderFilter
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('verhuurder.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
