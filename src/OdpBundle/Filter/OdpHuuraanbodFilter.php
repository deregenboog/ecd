<?php

namespace OdpBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter;

class OdpHuuraanbodFilter
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var AppDateRangeModel
     */
    public $startdatum;

    /**
     * @var AppDateRangeModel
     */
    public $einddatum;

    /**
     * @var bool
     */
    public $openstaand;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('odpHuuraanbod.id = :odp_klant_id')
                ->setParameter('odp_klant_id', $this->id)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('odpHuuraanbod.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('odpHuuraanbod.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->einddatum) {
            if ($this->einddatum->getStart()) {
                $builder
                    ->andWhere('odpHuuraanbod.einddatum >= :einddatum_van')
                    ->setParameter('einddatum_van', $this->einddatum->getStart())
                ;
            }
            if ($this->einddatum->getEnd()) {
                $builder
                    ->andWhere('odpHuuraanbod.einddatum <= :einddatum_tot')
                    ->setParameter('einddatum_tot', $this->einddatum->getEnd())
                ;
            }
        }

        if ($this->openstaand) {
            $builder->where('odpHuurovereenkomst.id IS NULL');
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
