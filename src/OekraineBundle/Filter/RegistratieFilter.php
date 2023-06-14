<?php

namespace OekraineBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Locatie;

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
     * @var Locatie
     */
    public $woonlocatie;

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

        if ($this->woonlocatie) {
            $builder
                ->innerJoin("registratie.bezoeker.intake","intake")
                ->andWhere('intake.woonlocatie = :woonlocatie')
                ->setParameter('woonlocatie', $this->woonlocatie)
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
            $this->klant->applyTo($builder,'appKlant');
        }
    }
}
