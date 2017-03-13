<?php

namespace GaBundle\Filter;

use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;

class GaKlantIntakeFilter extends GaIntakeFilter
{
    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        parent::applyTo($builder);

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
