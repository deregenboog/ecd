<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Activiteit;
use AppBundle\Form\Model\AppDateRangeModel;

class KlusFilter implements FilterInterface
{
    const STATUS_OPEN = 'Openstaand';
    const STATUS_ON_HOLD = 'On hold';
    const STATUS_CLOSED = 'Afgerond';

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
            switch($this->status) {
                case self::STATUS_OPEN:
                    $builder
                        ->andWhere('klus.einddatum IS NULL OR klus.einddatum > :today')
                        ->andWhere('klus.onHold = false')
                        ->setParameter('today', new \DateTime('today'))
                    ;
                    break;
                case self::STATUS_ON_HOLD:
                    $builder
                        ->andWhere('klus.einddatum IS NULL OR klus.einddatum > :today')
                        ->andWhere('klus.onHold = true')
                        ->setParameter('today', new \DateTime('today'))
                    ;
                    break;
                case self::STATUS_CLOSED:
                    $builder
                        ->andWhere('klus.einddatum <= :today')
                        ->setParameter('today', new \DateTime('today'))
                    ;
                    break;
                default:
                    break;
            }
        }

        if ($this->activiteit) {
            $builder
                ->andWhere('klus.activiteit = :activiteit')
                ->setParameter('activiteit', $this->activiteit)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
