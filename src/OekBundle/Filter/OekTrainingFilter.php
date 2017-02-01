<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\OekGroep;

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
     * @var \DateTime
     */
    public $startDatum;

    /**
     * @var \DateTime
     */
    public $eindDatum;

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

        if ($this->startDatum) {
            if ($this->startDatum->getStart()) {
                $builder
                    ->andWhere('oekTraining.startDatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startDatum->getStart())
                ;
            }
            if ($this->startDatum->getEnd()) {
                $builder
                    ->andWhere('oekTraining.startDatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startDatum->getEnd())
                ;
            }
        }

        if ($this->eindDatum) {
            if ($this->eindDatum->getStart()) {
                $builder
                    ->andWhere('oekTraining.eindDatum >= :einddatum_van')
                    ->setParameter('einddatum_van', $this->eindDatum->getStart())
                ;
            }
            if ($this->eindDatum->getEnd()) {
                $builder
                    ->andWhere('oekTraining.eindDatum <= :einddatum_tot')
                    ->setParameter('einddatum_tot', $this->eindDatum->getEnd())
                ;
            }
        }
    }
}
