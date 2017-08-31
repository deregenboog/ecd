<?php

namespace OdpBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter;

class VerhuurderFilter
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var \DateTime
     */
    public $aanmelddatum;

    /**
     * @var \DateTime
     */
    public $afsluitdatum;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var bool
     */
    public $wpi;
    
    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('verhuurder.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                ->andWhere('verhuurder.aanmelddatum >= :aanmelddatum_van')
                ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                ->andWhere('verhuurder.aanmelddatum <= :aanmelddatum_tot')
                ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                ->andWhere('verhuurder.afsluitdatum >= :afsluitdatum_van')
                ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                ->andWhere('verhuurder.afsluitdatum <= :afsluitdatum_tot')
                ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->wpi) {
            $builder
                ->andWhere('verhuurder.wpi = :wpi')
                ->setParameter('wpi', $this->wpi)
            ;
        }
        
        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
