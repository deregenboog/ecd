<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Activiteit;
use HsBundle\Entity\Arbeider;
use HsBundle\Entity\Klus;

class RegistratieFilter implements FilterInterface
{
    /**
     * @var Klus
     */
    public $klus;

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
        if($this->klus)
        {
            $builder
                ->andWhere('registratie.klus = :klus')
                ->setParameter('klus',$this->klus)
                ;
        }

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
