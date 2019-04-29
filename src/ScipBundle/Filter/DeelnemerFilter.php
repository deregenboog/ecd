<?php

namespace ScipBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;
use ScipBundle\Entity\Project;

class DeelnemerFilter implements FilterInterface
{
    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $type;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var bool
     */
    public $actief = true;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->label) {
            $builder->andWhere('label = :label')->setParameter('label', $this->label);
        }

        if ($this->type) {
            $builder->andWhere('deelnemer.type = :type')->setParameter('type', $this->type);
        }

        if ($this->project) {
            $builder->andWhere('project = :project')->setParameter('project', $this->project);
        }

        if ($this->actief) {
            $builder->andWhere('project.id IS NOT NULL');
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
