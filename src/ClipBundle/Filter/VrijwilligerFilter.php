<?php

namespace ClipBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use ClipBundle\Entity\Locatie;
use Doctrine\ORM\QueryBuilder;

class VrijwilligerFilter implements FilterInterface
{
    public $alias = 'vrijwilliger';

    /**
     * @var \AppBundle\Filter\VrijwilligerFilter
     */
    public $vrijwilliger;

    /**
     * @var AppDateRangeModel
     */
    public $aanmelddatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var Locatie
     */
    public $locatie;

    /**
     * @var bool
     */
    public $actief;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->vrijwilliger) {
            //            $this->vrijwilliger->applyTo($builder, 'appVrijwilliger');
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

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('vrijwilliger.afsluitdatum >= :afsluitdatum_start')
                    ->setParameter('afsluitdatum_start', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('vrijwilliger.afsluitdatum <= :afsluitdatum_end')
                    ->setParameter('afsluitdatum_end', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->locatie) {
            $builder
                ->andWhere('locatie = :locatie')
                ->setParameter('locatie', $this->locatie)
            ;
        }

        if (true == $this->actief) {
            $builder
                ->andWhere('vrijwilliger.afsluitdatum IS NULL')
            ;
        }
    }
}
