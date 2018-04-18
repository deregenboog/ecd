<?php

namespace IzBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\VrijwilligerFilter;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;
use IzBundle\Entity\Koppelingstatus;
use IzBundle\Entity\IzKlant;

class KoppelingFilter implements FilterInterface
{
    /**
     * @var HulpvraagFilter
     */
    public $hulpvraag;

    /**
     * @var IzKlant
     */
    public $izKlant;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    /**
     * @var \DateTime
     */
    public $startdatum;

    /**
     * @var \DateTime
     */
    public $afsluitdatum;

    /**
     * @var bool
     */
    public $lopendeKoppelingen;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Medewerker
     */
    public $hulpvraagMedewerker;

    /**
     * @var Medewerker
     */
    public $hulpaanbodMedewerker;

    /**
     * @var Koppelingstatus
     */
    public $status;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->hulpvraag) {
            $this->hulpvraag->applyTo($builder);
        }

        if ($this->izKlant) {
            $this->izKlant->applyTo($builder);
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }

        if ($this->startdatum) {
            $builder
                ->andWhere('koppeling.startdatum = :startdatum')
                ->setParameter('startdatum', $this->startdatum)
            ;
        }

        if ($this->afsluitdatum) {
            $builder
                ->andWhere('koppeling.afsluitdatum = :afsluitdatum')
                ->setParameter('afsluitdatum', $this->afsluitdatum)
            ;
        }

        if ($this->lopendeKoppelingen) {
            $builder
                ->andWhere('koppeling.afsluitdatum IS NULL OR koppeling.afsluitdatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->project) {
            $builder
                ->andWhere('hulpvraag.project = :project OR hulpaanbod.project = :project')
                ->setParameter('project', $this->project)
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('hulpvraag.medewerker = :medewerker OR hulpaanbod.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }

        if ($this->hulpvraagMedewerker) {
            $builder
                ->andWhere('hulpvraag.medewerker = :hulpvraagMedewerker')
                ->setParameter('hulpvraagMedewerker', $this->hulpvraagMedewerker)
            ;
        }

        if ($this->hulpaanbodMedewerker) {
            $builder
                ->andWhere('hulpaanbod.medewerker = :hulpaanbodMedewerker')
                ->setParameter('hulpaanbodMedewerker', $this->hulpaanbodMedewerker)
            ;
        }

        if ($this->status) {
            $builder
                ->andWhere('koppeling.status = :status')
                ->setParameter('status', $this->status)
            ;
        }
    }
}
