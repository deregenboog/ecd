<?php

namespace GaBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;

abstract class GaIntakeFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var AppDateRangeModel
     */
    public $intakedatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var bool
     */
    public $open;

    /**
     * @var Medewerker
     */
    public $medewerker;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->intakedatum) {
            if ($this->intakedatum->getStart()) {
                $builder
                    ->andWhere('intake.intakedatum >= :intakedatum_van')
                    ->setParameter('intakedatum_van', $this->intakedatum->getStart())
                ;
            }
            if ($this->intakedatum->getEnd()) {
                $builder
                    ->andWhere('intake.intakedatum <= :intakedatum_tot')
                    ->setParameter('intakedatum_tot', $this->intakedatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('intake.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('intake.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->open) {
            $builder->andWhere('intake.afsluitdatum IS NULL');
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('intake.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }
    }
}
