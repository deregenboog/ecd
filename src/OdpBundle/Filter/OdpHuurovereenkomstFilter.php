<?php

namespace OdpBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter;

class OdpHuurovereenkomstFilter
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
     * @var KlantFilter
     */
    public $odpHuurderKlant;

    /**
     * @var KlantFilter
     */
    public $odpVerhuurderKlant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('odpHuurovereenkomst.id = :odp_klant_id')
                ->setParameter('odp_klant_id', $this->id)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('odpHuurovereenkomst.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('odpHuurovereenkomst.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->einddatum) {
            if ($this->einddatum->getStart()) {
                $builder
                    ->andWhere('odpHuurovereenkomst.einddatum >= :einddatum_van')
                    ->setParameter('einddatum_van', $this->einddatum->getStart())
                ;
            }
            if ($this->einddatum->getEnd()) {
                $builder
                    ->andWhere('odpHuurovereenkomst.einddatum <= :einddatum_tot')
                    ->setParameter('einddatum_tot', $this->einddatum->getEnd())
                ;
            }
        }

        if ($this->odpHuurderKlant) {
            $this->odpHuurderKlant->applyTo($builder, 'odpHuurderKlant');
        }

        if ($this->odpVerhuurderKlant) {
            $this->odpVerhuurderKlant->applyTo($builder, 'odpVerhuurderKlant');
        }
    }
}
