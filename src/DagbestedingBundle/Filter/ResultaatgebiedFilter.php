<?php

namespace DagbestedingBundle\Filter;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use Doctrine\ORM\QueryBuilder;

class ResultaatgebiedFilter implements FilterInterface
{
    /**
     * @var Resultaatgebiedsoort
     */
    public $soort;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->soort) {
            $builder
                ->andWhere('resultaatgebied.soort = :soort')
                ->setParameter('soort', $this->soort)
            ;
        }
    }
}
