<?php

namespace DagbestedingBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;

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
