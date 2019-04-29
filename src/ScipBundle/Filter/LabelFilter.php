<?php

namespace ScipBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class LabelFilter implements FilterInterface
{
    public $id;

    public $naam;

    public $actief;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder->andWhere('label.id = :id')->setParameter('id', $this->id);
        }

        if ($this->naam) {
            $builder->andWhere('label.naam LIKE :naam')->setParameter('naam', '%'.$this->naam.'%');
        }

        if (!is_null($this->actief)) {
            $builder->andWhere('label.actief = :actief')->setParameter('actief', (bool) $this->actief);
        }
    }
}
