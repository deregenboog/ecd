<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter;

class BetalingFilter implements FilterInterface
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
     * @var FactuurFilter
     */
    public $factuur;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->referentie) {
            $builder
                ->andWhere('betaling.referentie LIKE :referentie')
                ->setParameter('referentie', "%{$this->referentie}%")
            ;
        }

        if ($this->datum instanceof \DateTime) {
            $builder
                ->andWhere('betaling.datum = :datum')
                ->setParameter('datum', $this->datum)
            ;
        }

        if ($this->bedrag) {
            $builder
                ->andWhere('betaling.bedrag = :bedrag')
                ->setParameter('bedrag', $this->bedrag)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->factuur) {
            $this->factuur->applyTo($builder);
        }
    }
}
