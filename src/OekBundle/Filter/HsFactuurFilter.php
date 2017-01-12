<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class HsFactuurFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $nummer;

    /**
     * @var \DateTime
     */
    public $datum = null;

    /**
     * @var float
     */
    public $bedrag;

    /**
     * @var bool
     */
    public $openstaand;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->nummer) {
            $builder
                ->andWhere('hsFactuur.nummer LIKE :nummer')
                ->setParameter('nummer', "%{$this->nummer}%")
            ;
        }

        if ($this->datum instanceof \DateTime) {
            $builder
                ->andWhere('hsFactuur.datum = :datum')
                ->setParameter('datum', $this->datum)
            ;
        }

        if ($this->bedrag) {
            $builder
                ->andWhere('hsFactuur.bedrag = :bedrag')
                ->setParameter('bedrag', $this->bedrag)
            ;
        }

        if ($this->openstaand) {
            $builder
                ->leftJoin('hsFactuur.hsBetalingen', 'hsBetaling')
                ->having('(SUM(hsFactuur.bedrag) - SUM(hsBetaling.bedrag)) > 0')
                ->orHaving('SUM(hsFactuur.bedrag) > 0 AND COUNT(hsBetaling) = 0')
                ->groupBy('hsFactuur')
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
