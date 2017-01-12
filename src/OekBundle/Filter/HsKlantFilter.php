<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class HsKlantFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

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
        if ($this->id) {
            $builder
                ->andWhere('hsKlant.id = :hs_klant_id')
                ->setParameter('hs_klant_id', $this->id)
            ;
        }

        if ($this->openstaand) {
            $builder
                ->innerJoin('hsKlant.hsKlussen', 'hsKlus')
                ->innerJoin('hsKlus.hsFacturen', 'hsFactuur')
                ->innerJoin('hsFactuur.hsBetalingen', 'hsBetaling')
                ->having('(SUM(hsFactuur.bedrag) - SUM(hsBetaling.bedrag)) > 0')
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
