<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\OekGroep;

class OekTrainingFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var OekGroep
     */
    public $training_oekGroep;

    /**
     * @var string
     */
    public $startDatum;

    /**
     * @var string
     */
    public $eindDatum;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('oekTraining.id = :oek_klant_id')
                ->setParameter('oek_klant_id', $this->id)
            ;
        }

        if ($this->training_oekGroep) {
            $builder
                ->andWhere('oekGroep = :oek_groep')
                ->setParameter('oek_groep', $this->training_oekGroep)
            ;
        }

        if ($this->startDatum) {
            $builder
                ->andWhere('oekTraining.startDatum LIKE :startDatum')
                ->setParameter('startDatum', "%{$this->startDatum}%")
            ;
        }

        if ($this->eindDatum) {
            $builder
                ->andWhere('oekTraining.eindDatum LIKE :eindDatum')
                ->setParameter('eindDatum', "%{$this->eindDatum}%")
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
