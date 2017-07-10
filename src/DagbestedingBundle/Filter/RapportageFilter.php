<?php

namespace DagbestedingBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;

class RapportageFilter implements FilterInterface
{
    /**
     * @var \DateTime
     */
    public $datum;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('rapportage.datum >= :datum_van')
                    ->setParameter('datum_van', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('rapportage.datum <= :datum_tot')
                    ->setParameter('datum_tot', $this->datum->getEnd())
                ;
            }
        }
    }
}
