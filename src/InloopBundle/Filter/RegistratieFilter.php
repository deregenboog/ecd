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
     * @var bool
     */
    public $maaltijd;

    /**
     * @var bool
     */
    public $activering;

    /**
     * @var bool
     */
    public $kleding;

    /**
     * @var bool
     */
    public $veegploeg;

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

        $props = ['maaltijd', 'activering', 'kleding', 'veegploeg'];
        foreach ($props as $prop) {
            if (!is_null($this->{$prop})) {
                $builder->andWhere("registratie.{$prop} = :{$prop}")->setParameter($prop, $this->{$prop});
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
