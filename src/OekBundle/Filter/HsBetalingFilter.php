<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter;

class HsBetalingFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $referentie;

    /**
     * @var \DateTime
     */
    public $datum;

    /**
     * @var float
     */
    public $bedrag;

    /**
     * @var HsFactuurFilter
     */
    public $hsFactuur;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->referentie) {
            $builder
                ->andWhere('hsBetaling.referentie LIKE :referentie')
                ->setParameter('referentie', "%{$this->referentie}%")
            ;
        }

        if ($this->datum instanceof \DateTime) {
            $builder
                ->andWhere('hsBetaling.datum = :datum')
                ->setParameter('datum', $this->datum)
            ;
        }

        if ($this->bedrag) {
            $builder
                ->andWhere('hsBetaling.bedrag = :bedrag')
                ->setParameter('bedrag', $this->bedrag)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->hsFactuur) {
            $this->hsFactuur->applyTo($builder);
        }
    }
}
