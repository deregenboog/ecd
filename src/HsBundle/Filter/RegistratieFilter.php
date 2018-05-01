<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Arbeider;
use AppBundle\Form\Model\AppDateRangeModel;
use HsBundle\Entity\Activiteit;

class RegistratieFilter implements FilterInterface
{
    /**
     * @var Arbeider
     */
    public $arbeider;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var Activiteit
     */
    public $activiteit;

    /**
     * @var AppDateRangeModel
     */
    public $datum;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->arbeider) {
            $builder
                ->andWhere('registratie.arbeider = :arbeider')
                ->setParameter('arbeider', $this->arbeider)
            ;
        }

        if ($this->activiteit) {
            $builder
                ->andWhere('registratie.activiteit = :activiteit')
                ->setParameter('activiteit', $this->activiteit)
            ;
        }

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('registratie.datum >= :datum_van')
                    ->setParameter('datum_van', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('registratie.datum <= :datum_tot')
                    ->setParameter('datum_tot', $this->datum->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
