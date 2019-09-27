<?php

namespace InloopBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\RecenteRegistratie;

class RegistratieHistoryFilter extends RegistratieFilter
{
    public function __construct(Locatie $locatie)
    {
        $this->locatie = $locatie;
        $this->buiten = new AppDateRangeModel(new \DateTime('today'));
    }

    public function applyTo(QueryBuilder $builder)
    {
        $builder->innerJoin(RecenteRegistratie::class, 'recenteRegistratie', 'WITH', 'registratie = recenteRegistratie.registratie');

        parent::applyTo($builder);
    }
}
