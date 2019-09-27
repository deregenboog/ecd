<?php

namespace InloopBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

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
    public $douche;

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
     * @var bool
     */
    public $mw;

    /**
     * @var AppKlantFilter
     */
    public $klant;

    public function __construct(Locatie $locatie)
    {
        $this->locatie = $locatie;
    }

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

        if (0 === $this->douche) {
            $builder->andWhere('registratie.douche = 0');
        } elseif (1 === $this->douche) {
            $builder->andWhere('registratie.douche <> 0');
        }

        if (0 === $this->mw) {
            $builder->andWhere('registratie.mw = 0');
        } elseif (1 === $this->mw) {
            $builder->andWhere('registratie.mw <> 0');
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
