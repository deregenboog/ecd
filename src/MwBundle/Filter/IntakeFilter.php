<?php

namespace MwBundle\Filter;

use AppBundle\Entity\Werkgebied;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class IntakeFilter extends \InloopBundle\Filter\IntakeFilter implements FilterInterface
{
    /**
     * @var Werkgebied
     */
    public $werkgebied;


    public function applyTo(QueryBuilder $builder)
    {

        if ($this->werkgebied) {
            $builder
                ->andWhere('werkgebied = :werkgebied')
                ->setParameter('werkgebied', $this->werkgebied)
            ;
        }

        parent::applyTo($builder);

    }
}
