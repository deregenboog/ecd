<?php

namespace InloopBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class VrijwilligerFilter implements FilterInterface
{
    public $alias = 'vrijwilliger';

    /**
     * @var AppBundle\Filter\VrijwilligerFilter
     */
    public $vrijwilliger;

    /**
     * @var AppDateRangeModel
     */
    public $aanmelddatum;

    /**
     * @var Locatie
     */
    public $locatie;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder, 'appVrijwilliger');
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere('vrijwilliger.aanmelddatum >= :aanmelddatum_start')
                    ->setParameter('aanmelddatum_start', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere('vrijwilliger.aanmelddatum <= :aanmelddatum_end')
                    ->setParameter('aanmelddatum_end', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if ($this->locatie) {
            $builder->andWhere('locatie = :locatie')->setParameter('locatie', $this->locatie);
        }
    }
}
