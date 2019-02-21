<?php

namespace GaBundle\Filter;

use AppBundle\Filter\VrijwilligerFilter;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Medewerker;

class VrijwilligerdossierFilter extends DossierFilter
{
    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    public function applyTo(QueryBuilder $builder)
    {
        parent::applyTo($builder);

        if ($this->medewerker) {
            $builder
                ->andWhere('medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }
    }
}
