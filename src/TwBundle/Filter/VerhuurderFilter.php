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
     * @var bool
     */
    public $gekoppeld = null;

    /**
     * @var KlantFilter
     */
    public $appKlant;

    /**
     * @var Medewerker
     */
    public $ambulantOndersteuner;

    /**
     * @var Medewerker
     */
    public $medewerker = null;

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
        if($this->project && count($this->project) > 0)
        {
            $builder
                ->andWhere('verhuurder.project IN (:project)')
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



        if ($this->actief) {
            $builder
            ->andWhere('verhuurder.aanmelddatum <= :today')
            ->andWhere('verhuurder.afsluitdatum > :today OR verhuurder.afsluitdatum IS NULL')
            ->setParameter('today', new \DateTime('today'))
            ;
        }

        if ($this->gekoppeld) {
            $builder
//                ->andWhere('verhuurder.aanmelddatum <= :today')
//                ->andWhere('verhuurder.afsluitdatum > :today OR verhuurder.afsluitdatum IS NULL')
//                ->setParameter('today', new \DateTime('today'))
            ;
        }

        if($this->medewerker)
        {

            $builder
//                ->leftJoin('huurder.ambulantOndersteuner','ambulantOndersteuner')
//                ->andWhere('ambulantOndersteuner IS NULL')
                ->andWhere('verhuurder.medewerker = :medewerker')

                ->setParameter('medewerker',$this->medewerker);
        }

        if ($this->appKlant) {
            $this->appKlant->applyTo($builder,'appKlant');
        }
    }
}
