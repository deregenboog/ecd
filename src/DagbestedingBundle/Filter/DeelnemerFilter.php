<?php

namespace DagbestedingBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;

class DeelnemerFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var AppDateRangeModel
     */
    public $aanmelddatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var bool
     */
    public $zonderTraject;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var bool
     */
    public $actief = true;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('deelnemer.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('deelnemer.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere('deelnemer.aanmelddatum >= :aanmelddatum_van')
                    ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere('deelnemer.aanmelddatum <= :aanmelddatum_tot')
                    ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('deelnemer.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('deelnemer.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->actief) {
            $builder->andWhere('(deelnemer.afsluitdatum IS NULL OR deelnemer.afsluitdatum >= DATE(\'now\'))');
        }
        if ($this->zonderTraject) {
            $builder->leftJoin('deelnemer.trajecten', 'traject')->andWhere('traject.id IS NULL');
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
