<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class OekKlantFilter implements FilterInterface
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
                ->andWhere('oekKlant.id = :oek_klant_id')
                ->setParameter('oek_klant_id', $this->id)
            ;
        }

        if ($this->openstaand) {
            $builder
                ->innerJoin('oekKlant.oekKlussen', 'oekKlus')
                ->innerJoin('oekKlus.oekFacturen', 'oekFactuur')
                ->innerJoin('oekFactuur.oekBetalingen', 'oekBetaling')
                ->having('(SUM(oekFactuur.bedrag) - SUM(oekBetaling.bedrag)) > 0')
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
