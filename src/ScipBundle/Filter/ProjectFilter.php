<?php

namespace ScipBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class ProjectFilter implements FilterInterface
{
    public $id;

    public $naam;

    public $kpl;

    public $actief;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder->andWhere('project.id = :id')->setParameter('id', $this->id);
        }

        if ($this->naam) {
            $builder->andWhere('project.naam LIKE :naam')->setParameter('naam', '%'.$this->naam.'%');
        }

        if ($this->kpl) {
            $builder->andWhere('project.kpl = :kpl')->setParameter('kpl', $this->kpl);
        }

        if (!is_null($this->actief)) {
            $builder->andWhere('project.actief = :actief')->setParameter('actief', (bool) $this->actief);
        }
    }
}
