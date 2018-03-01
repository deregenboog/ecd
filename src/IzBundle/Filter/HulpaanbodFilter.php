<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\VrijwilligerFilter;

class HulpaanbodFilter
{
    /**
     * @var \DateTime
     */
    public $startdatum;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

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
        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }

        if ($this->startdatum) {
            $builder
                ->andWhere('hulpaanbod.startdatum = :startdatum')
                ->setParameter('startdatum', $this->startdatum)
            ;
        }

        if ($this->project) {
            $builder
                ->andWhere('hulpaanbod.project = :project')
                ->setParameter('project', $this->project)
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('hulpaanbod.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }
    }
}
