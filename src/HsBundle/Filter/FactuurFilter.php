<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;

class FactuurFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $nummer;

    /**
     * @var AppDateRangeModel
     */
    public $datum;

    /**
     * @var float
     */
    public $bedrag;

    /**
     * @var bool
     */
    public $status;

    /**
     * @var bool
     */
    public $negatiefSaldo;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var bool
     */
    public $metHerinnering;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->nummer) {
            $builder
                ->andWhere('factuur.nummer LIKE :nummer')
                ->setParameter('nummer', "%{$this->nummer}%")
            ;
        }

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('factuur.datum >= :datum_van')
                    ->setParameter('datum_van', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('factuur.datum <= :datum_tot')
                    ->setParameter('datum_tot', $this->datum->getEnd())
                ;
            }
        }

        if ($this->bedrag) {
            $builder
                ->andWhere('factuur.bedrag = :bedrag')
                ->setParameter('bedrag', $this->bedrag)
            ;
        }

        if (null !== $this->status) {
            $builder
                ->andWhere('factuur.locked = :locked')
                ->setParameter('locked', (bool) $this->status)
            ;
        }

        if ($this->negatiefSaldo) {
            $builder
                ->having('(factuur.bedrag - SUM(betaling.bedrag)) > 0')
                ->orHaving('factuur.bedrag > 0 AND COUNT(betaling) = 0')
                ->groupBy('factuur')
            ;
        }

        if ($this->metHerinnering) {
            $builder->andWhere('herinnering.id IS NOT NULL');
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
