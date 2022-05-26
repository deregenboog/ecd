<?php

namespace DagbestedingBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use DagbestedingBundle\Entity\Locatie;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Entity\Trajectcoach;
use DagbestedingBundle\Entity\Trajectsoort;
use Doctrine\ORM\QueryBuilder;

class TrajectFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var Trajectsoort
     */
    public $soort;

    /**
     * @var ResultaatgebiedFilter
     */
    public $resultaatgebied;

    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Trajectcoach
     */
    public $trajectcoach;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var Locatie
     */
    public $locatie;

    /**
     * @var \DateTime
     */
    public $startdatum;

    /**
     * @var \DateTime
     */
    public $evaluatiedatum;

    /**
     * @var \DateTime
     */
    public $einddatum;

    /**
     * @var \DateTime
     */
    public $afsluitdatum;

    /**
     * @var bool
     */
    public $actief = true;

    /**
     * @var bool
     */
    public $afwezig;

    /**
     * @var bool
     */
    public $verlenging;

    /**
     * @var bool
     */
    public $zonderOndersteuningsplan;


    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('traject.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->soort) {
            $builder
                ->andWhere('traject.soort = :soort')
                ->setParameter('soort', $this->soort)
            ;
        }

        if ($this->resultaatgebied) {
            $this->resultaatgebied->applyTo($builder);
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('trajectcoach.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }

        if ($this->trajectcoach) {
            $builder
                ->andWhere('traject.trajectcoach = :trajectcoach')
                ->setParameter('trajectcoach', $this->trajectcoach)
            ;
        }

        if ($this->project) {
            $builder
                ->innerJoin('deelnames.project', 'project', 'WITH', 'project = :project')
                ->setParameter('project', $this->project)
            ;
        }

        if ($this->locatie) {
            $builder
                ->innerJoin('traject.locaties', 'locatie', 'WITH', 'locatie = :locatie')
                ->setParameter('locatie', $this->locatie)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('traject.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('traject.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->evaluatiedatum) {
            if ($this->evaluatiedatum->getStart()) {
                $builder
                    ->andWhere('traject.evaluatiedatum >= :evaluatiedatum_van')
                    ->setParameter('evaluatiedatum_van', $this->evaluatiedatum->getStart())
                ;
            }
            if ($this->evaluatiedatum->getEnd()) {
                $builder
                    ->andWhere('traject.evaluatiedatum <= :evaluatiedatum_tot')
                    ->setParameter('evaluatiedatum_tot', $this->evaluatiedatum->getEnd())
                ;
            }
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('traject.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('traject.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }


        if ($this->einddatum) {
            if ($this->einddatum->getStart()) {
                $builder
                    ->andWhere('traject.einddatum >= :einddatum_van')
                    ->setParameter('einddatum_van', $this->einddatum->getStart())
                ;
            }
            if ($this->einddatum->getEnd()) {
                $builder
                    ->andWhere('traject.einddatum <= :einddatum_tot')
                    ->setParameter('einddatum_tot', $this->einddatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('traject.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('traject.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->actief) {
            $builder
                ->andWhere('traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today')
                ->setParameter('today', new \DateTime('today'))
            ;
        }


        if ($this->afwezig) {
            $builder
                ->leftJoin('traject.dagdelen', 'dagdeel', 'WITH', 'dagdeel.datum >= :two_weeks_ago')
                ->andWhere('dagdeel.id IS NULL OR dagdeel.aanwezigheid NOT IN (:aanwezig)')
                ->setParameter('aanwezig', ['A', 'V'])
                ->setParameter('two_weeks_ago', new \DateTime('-2 weeks'))
            ;
        }

        if ($this->verlenging) {
            $builder
                ->andWhere('traject.einddatum <= :two_months_ago')
                ->setParameter('two_months_ago', new \DateTime('+2 months'))
            ;
        }

        if ($this->zonderOndersteuningsplan) {
            $builder
                ->andWhere('traject.startdatum <= :today')
                ->andWhere('traject.ondersteuningsplanVerwerkt IS NULL OR traject.ondersteuningsplanVerwerkt = false')
                ->setParameter('today', new \DateTime('today'))
            ;
        }
    }
}
