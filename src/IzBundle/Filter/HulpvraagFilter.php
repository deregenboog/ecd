<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;

class HulpvraagFilter
{
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

        if ($this->medewerker) {
            $builder
                ->andWhere('hulpvraag.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }
    }
}
