<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;

class BetalingFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $referentie;

    /**
     * @var AppDateRangeModel
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

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('betaling.datum >= :datum_van')
                    ->setParameter('datum_van', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('betaling.datum <= :datum_tot')
                    ->setParameter('datum_tot', $this->datum->getEnd())
                ;
            }
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
