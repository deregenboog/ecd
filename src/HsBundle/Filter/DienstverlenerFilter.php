<?php

namespace HsBundle\Filter;

use Doctrine\ORM\QueryBuilder;

class DienstverlenerFilter extends ArbeiderFilter
{
    public $alias = 'dienstverlener';

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
