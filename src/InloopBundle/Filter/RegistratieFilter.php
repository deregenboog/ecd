<?php

namespace InloopBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Locatie;
use AppBundle\Form\Model\AppDateRangeModel;

class RegistratieFilter implements FilterInterface
{
    /**
     * @var Locatie
     */
    public $locatie;

    /**
     * @var AppDateRangeModel
     */
    public $binnen;

    /**
     * @var AppDateRangeModel
     */
    public $buiten;

    /**
     * @var AppKlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->locatie) {
            $builder
                ->andWhere('locatie = :locatie')
                ->setParameter('locatie', $this->locatie)
            ;
        }

        if ($this->binnen) {
            if ($this->binnen->getStart()) {
                $builder
                    ->andWhere('DATE(registratie.binnen) >= :datum_van_start')
                    ->setParameter('datum_van_start', $this->binnen->getStart())
                ;
            }
            if ($this->binnen->getEnd()) {
                $builder
                    ->andWhere('DATE(registratie.binnen) <= :datum_van_end')
                    ->setParameter('datum_van_end', $this->binnen->getEnd())
                ;
            }
        }

        if ($this->buiten) {
            if ($this->buiten->getStart()) {
                $builder
                    ->andWhere('DATE(registratie.buiten) >= :datum_tot_start')
                    ->setParameter('datum_tot_start', $this->buiten->getStart())
                ;
            }
            if ($this->buiten->getEnd()) {
                $builder
                    ->andWhere('DATE(registratie.buiten) <= :datum_tot_end')
                    ->setParameter('datum_tot_end', $this->buiten->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
