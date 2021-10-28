<?php

namespace TwBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;
use TwBundle\Entity\Project;

class VerhuurderFilter implements FilterInterface
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
     * @var bool
     */
    public $actief;

    /**
     * @var KlantFilter
     */
    public $appKlant;

    /**
     * @var bool
     */
    public $wpi;

    /**
     * @var bool
     */
    public $ksgw;

    /**
     * @var Medewerker
     */
    public $ambulantOndersteuner;

    /**
     * @var Project
     */
    public $project;


    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('verhuurder.id = :id')
                ->setParameter('id', $this->id)
            ;
        }
        if($this->project)
        {
            $builder
                ->andWhere('verhuurder.project = :project')
                ->setParameter("project",$this->project);
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

        if ($this->actief) {
            $builder
            ->andWhere('verhuurder.aanmelddatum <= :today')
            ->andWhere('verhuurder.afsluitdatum > :today OR verhuurder.afsluitdatum IS NULL')
            ->setParameter('today', new \DateTime('today'))
            ;
        }

        if ($this->wpi) {
            $builder
                ->andWhere('verhuurder.wpi = :wpi')
                ->setParameter('wpi', $this->wpi)
            ;
        }

        if ($this->ksgw) {
            $builder
                ->andWhere('verhuurder.ksgw = :ksgw')
                ->setParameter('ksgw', $this->ksgw)
            ;
        }
        if($this->ambulantOndersteuner)
        {

            $builder
//                ->leftJoin('huurder.ambulantOndersteuner','ambulantOndersteuner')
//                ->andWhere('ambulantOndersteuner IS NULL')
                ->andWhere('ambulantOndersteuner = :ambulantOndersteuner')

                ->setParameter('ambulantOndersteuner',$this->ambulantOndersteuner);
        }

        if ($this->appKlant) {
            $this->appKlant->applyTo($builder,'appKlant');
        }
    }
}
