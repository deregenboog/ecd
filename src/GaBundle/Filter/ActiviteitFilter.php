<?php

namespace GaBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Form\Model\AppTimeRangeModel;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Groep;

class ActiviteitFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $naam;

    /**
     * @var Groep
     */
    public $groep;

    /**
     * @var AppDateRangeModel
     */
    public $datum;

    /**
     * @var AppTimeRangeModel
     */
    public $tijd;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->naam) {
            $parts = preg_split('/\s+/', $this->naam);
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("activiteit.naam LIKE :naam_part_{$i}")
                    ->setParameter("naam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->groep) {
            $builder
                ->andWhere('groep = :groep')
                ->setParameter('groep', $this->groep)
            ;
        }

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('activiteit.datum >= :datum_van')
                    ->setParameter('datum_van', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('activiteit.datum <= :datum_tot')
                    ->setParameter('datum_tot', $this->datum->getEnd())
                ;
            }
        }

        if ($this->tijd) {
            if ($this->tijd->getStart()) {
                $builder
                    ->andWhere('TIME(activiteit.tijd) >= :tijd_van')
                    ->setParameter('tijd_van', $this->tijd->getStart()->format('H:i:s'))
                ;
            }
            if ($this->tijd->getEnd()) {
                $builder
                    ->andWhere('TIME(activiteit.tijd) <= :tijd_tot')
                    ->setParameter('tijd_tot', $this->tijd->getEnd()->format('H:i:s'))
                ;
            }
        }
    }
}
