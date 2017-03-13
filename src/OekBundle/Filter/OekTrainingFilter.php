<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\OekGroep;
use AppBundle\Form\Model\AppDateRangeModel;

class OekTrainingFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $naam;

    /**
     * @var OekGroep
     */
    public $oekGroep;

    /**
     * @var AppDateRangeModel
     */
    public $startdatum;

    /**
     * @var AppDateRangeModel
     */
    public $einddatum;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('oekTraining.id = :oek_klant_id')
                ->setParameter('oek_klant_id', $this->id)
            ;
        }

        if ($this->naam) {
            $builder
                ->andWhere('oekTraining.naam LIKE :naam')
                ->setParameter('naam', "%{$this->naam}%")
            ;
        }

        if ($this->oekGroep) {
            $builder
                ->andWhere('oekGroep = :oek_groep')
                ->setParameter('oek_groep', $this->oekGroep)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('oekTraining.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('oekTraining.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->einddatum) {
            if ($this->einddatum->getStart()) {
                $builder
                    ->andWhere('oekTraining.einddatum >= :einddatum_van')
                    ->setParameter('einddatum_van', $this->einddatum->getStart())
                ;
            }
            if ($this->einddatum->getEnd()) {
                $builder
                    ->andWhere('oekTraining.einddatum <= :einddatum_tot')
                    ->setParameter('einddatum_tot', $this->einddatum->getEnd())
                ;
            }
        }
    }
}
