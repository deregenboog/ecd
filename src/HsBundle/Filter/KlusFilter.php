<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Activiteit;

class KlusFilter implements FilterInterface
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
    public $zonderEinddatum;

    /**
     * @var string
     */
    public $status;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var Activiteit
     */
    public $activiteit;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('klus.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('klus.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('klus.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->einddatum) {
            if ($this->einddatum->getStart()) {
                $builder
                    ->andWhere('klus.einddatum >= :einddatum_van')
                    ->setParameter('einddatum_van', $this->einddatum->getStart())
                ;
            }
            if ($this->einddatum->getEnd()) {
                $builder
                    ->andWhere('klus.einddatum <= :einddatum_tot')
                    ->setParameter('einddatum_tot', $this->einddatum->getEnd())
                ;
            }
        }

        if ($this->zonderEinddatum) {
            $builder->andWhere('klus.einddatum IS NULL');
        }

        if ($this->status) {
            $builder
                ->andWhere('klus.status = :status')
                ->setParameter('status', $this->status)
            ;
        }

        if ($this->activiteit) {
            $builder
                ->andWhere('activiteit = :activiteit')
                ->setParameter('activiteit', $this->activiteit)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
