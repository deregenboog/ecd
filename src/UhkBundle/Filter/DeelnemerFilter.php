<?php

namespace UhkBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;

class DeelnemerFilter implements FilterInterface
{
    public $alias = 'deelnemer';

    /**
     * @var \AppBundle\Filter\KlantFilter
     */
    public $klant;

    /**
     * @var AppDateRangeModel
     */
    public $aanmelddatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->klant) {
            $this->klant->applyTo($builder, 'appVrijwilliger');
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
    }
}
