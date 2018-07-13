<?php

namespace IzBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Doelgroep;
use IzBundle\Entity\Hulpvraagsoort;
use IzBundle\Entity\Project;

class HulpvraagFilter implements FilterInterface
{
    /**
     * @var bool
     */
    public $matching = true;

    /**
     * @var \DateTime
     */
    public $startdatum;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var Hulpvraagsoort
     */
    public $hulpvraagsoort;

    /**
     * @var Doelgroep
     */
    public $doelgroep;

    /**
     * @var Medewerker
     */
    public $medewerker;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->startdatum) {
            $builder
                ->andWhere('hulpvraag.startdatum = :startdatum')
                ->setParameter('startdatum', $this->startdatum)
            ;
        }

        if ($this->project) {
            $builder
                ->andWhere('hulpvraag.project = :project')
                ->setParameter('project', $this->project)
            ;
        }

        if ($this->hulpvraagsoort) {
            $builder
                ->andWhere('hulpvraagsoort = :hulpvraagsoort')
                ->setParameter('hulpvraagsoort', $this->hulpvraagsoort)
            ;
        }

        if ($this->doelgroep) {
            $builder
                ->andWhere('doelgroep = :doelgroep')
                ->setParameter('doelgroep', $this->doelgroep)
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('hulpvraag.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }
    }
}
