<?php

namespace OdpBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\FilterInterface;

class HuuraanbodFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var AppDateRangeModel
     */
    public $startdatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var bool
     */
    public $actief;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('huuraanbod.id = :odp_klant_id')
                ->setParameter('odp_klant_id', $this->id)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('huuraanbod.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('huuraanbod.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('huuraanbod.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluit->getEnd()) {
                $builder
                    ->andWhere('huuraanbod.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->actief) {
            $builder
                ->andWhere('huuraanbod.afsluitdatum IS NULL OR huuraanbod.afsluitdatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
