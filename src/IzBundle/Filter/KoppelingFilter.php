<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\VrijwilligerFilter;

class KoppelingFilter implements FilterInterface
{
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
    public $koppelingStartdatum;

    /**
     * @var \DateTime
     */
    public $koppelingEinddatum;

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

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }

        if ($this->koppelingStartdatum) {
            $builder
                ->andWhere('hulpvraag.koppelingStartdatum = :koppelingStartdatum')
                ->setParameter('koppelingStartdatum', $this->koppelingStartdatum)
            ;
        }

        if ($this->koppelingEinddatum) {
            $builder
                ->andWhere('hulpvraag.koppelingEinddatum = :koppelingEinddatum')
                ->setParameter('koppelingEinddatum', $this->koppelingEinddatum)
            ;
        }

        if ($this->lopendeKoppelingen) {
            $builder
                ->andWhere('hulpvraag.koppelingEinddatum IS NULL OR hulpvraag.koppelingEinddatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->project) {
            $builder
                ->andWhere('hulpvraag.project = :project')
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
    }
}
