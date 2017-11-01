<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Entity\Werkgebied;

class DoelstellingFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $jaar;

    /**
     * @var IzProject
     */
    public $project;

    /**
     * @var Werkgebied
     */
    public $stadsdeel;

    /**
     * @var bool
     */
    public $centraleStad;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->jaar) {
            $builder
                ->andWhere('doelstelling.jaar = :jaar')
                ->setParameter('jaar', $this->jaar)
            ;
        }

        if ($this->project) {
            $builder
                ->andWhere('doelstelling.project = :project')
                ->setParameter('project', $this->project)
            ;
        }

        if ($this->centraleStad) {
            $builder
                ->andWhere('doelstelling.stadsdeel is null')
            ;
        }

        if ($this->stadsdeel) {
            $builder
                ->andWhere('doelstelling.stadsdeel = :stadsdeel')
                ->setParameter('stadsdeel', $this->stadsdeel)
            ;
        }
    }
}
