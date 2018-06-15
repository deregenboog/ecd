<?php

namespace DagbestedingBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Entity\Trajectbegeleider;
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
     * @var Trajectbegeleider
     */
    public $begeleider;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var \DateTime
     */
    public $startdatum;

    /**
     * @var \DateTime
     */
    public $einddatum;

    /**
     * @var \DateTime
     */
    public $afsluitdatum;

    /**
     * @var RapportageFilter
     */
    public $rapportage;

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

        if ($this->begeleider) {
            $builder
                ->andWhere('traject.begeleider = :begeleider')
                ->setParameter('begeleider', $this->begeleider)
            ;
        }

        if ($this->project) {
            $builder
                ->innerJoin('traject.projecten', 'project', 'WITH', 'project = :project')
                ->setParameter('project', $this->project)
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

        if ($this->rapportage) {
            $this->rapportage->applyTo($builder);
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
    }
}
