<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Arbeider;

class RegistratieFilter implements FilterInterface
{
    /**
     * @var Arbeider
     */
    public $arbeider;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->arbeider) {
            $builder
                ->andWhere('registratie.arbeider = :arbeider')
                ->setParameter('arbeider', $this->arbeider)
            ;
        }
    }
}
