<?php

namespace GaBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\VrijwilligerFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;

class GaVrijwilligerIntakeFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    /**
     * @var Medewerker
     */
    public $medewerker;

    public function applyTo(QueryBuilder $builder)
    {
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

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('intake.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }
    }
}
