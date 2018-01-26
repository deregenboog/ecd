<?php

namespace GaBundle\Filter;

use AppBundle\Entity\Werkgebied;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;

class GroepFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $naam;

    /**
     * @var Werkgebied
     */
    public $werkgebied;

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
        if ($this->naam) {
            $parts = preg_split('/\s+/', $this->naam);
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("groep.naam LIKE :naam_part_{$i}")
                    ->setParameter("naam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->werkgebied) {
            $builder
                ->andWhere('groep.werkgebied = :werkgebied')
                ->setParameter('werkgebied', $this->werkgebied)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('groep.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('groep.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->einddatum) {
            if ($this->einddatum->getStart()) {
                $builder
                    ->andWhere('groep.einddatum >= :einddatum_van')
                    ->setParameter('einddatum_van', $this->einddatum->getStart())
                ;
            }
            if ($this->einddatum->getEnd()) {
                $builder
                    ->andWhere('groep.einddatum <= :einddatum_tot')
                    ->setParameter('einddatum_tot', $this->einddatum->getEnd())
                ;
            }
        }
    }
}
