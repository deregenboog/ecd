<?php

namespace DagbestedingBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Entity\Traject;
use Doctrine\ORM\QueryBuilder;

class DagdeelFilter implements FilterInterface
{
    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var Traject
     */
    public $traject;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var AppDateRangeModel
     */
    public $datum;

    /**
     * @var string
     */
    public $dagdeel;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->dagdeel) {
            $builder
                ->andWhere('dagdeel.dagdeel = :dagdeel')
                ->setParameter('dagdeel', $this->dagdeel)
            ;
        }

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('dagdeel.datum >= :datum_van')
                    ->setParameter('datum_van', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('dagdeel.datum <= :datum_tot')
                    ->setParameter('datum_tot', $this->datum->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
