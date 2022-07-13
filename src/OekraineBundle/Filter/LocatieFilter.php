<?php

namespace OekraineBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class LocatieFilter implements FilterInterface
{
    /**
     * @var bool
     */
    public $actief;

    public function applyTo(QueryBuilder $builder)
    {
        if (null !== $this->actief) {
            if ($this->actief) {
                $builder
                    ->andWhere('locatie.datumVan <= :today')
                    ->andWhere('locatie.datumTot >= :today OR locatie.datumTot IS NULL')
                    ->setParameter('today', new \DateTime('today'))
                ;
            }
        }
    }
}
