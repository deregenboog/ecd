<?php

namespace ScipBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use ScipBundle\Entity\Project;

class ToegangsrechtFilter implements FilterInterface
{
    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Project
     */
    public $project;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->medewerker) {
            $builder
                ->andWhere('medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }

        if ($this->project) {
            $builder
                ->innerJoin('toegangsrecht.projecten', 'project')
                ->andWhere('project = :project')
                ->setParameter('project', $this->project)
            ;
        }
    }
}
