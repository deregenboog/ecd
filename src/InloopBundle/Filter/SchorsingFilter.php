<?php

namespace InloopBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class SchorsingFilter implements FilterInterface
{
    /**
     * @var Locatie
     */
    public $locatie;

    /**
     * @var AppDateRangeModel
     */
    public $datumVan;

    /**
     * @var AppDateRangeModel
     */
    public $datumTot;

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

        if ($this->datumVan) {
            if ($this->datumVan->getStart()) {
                $builder
                    ->andWhere('schorsing.datumVan >= :datum_van_start')
                    ->setParameter('datum_van_start', $this->datumVan->getStart())
                ;
            }
            if ($this->datumVan->getEnd()) {
                $builder
                    ->andWhere('schorsing.datumVan <= :datum_van_end')
                    ->setParameter('datum_van_end', $this->datumVan->getEnd())
                ;
            }
        }

        if ($this->datumTot) {
            if ($this->datumTot->getStart()) {
                $builder
                    ->andWhere('schorsing.datumTot >= :datum_tot_start')
                    ->setParameter('datum_tot_start', $this->datumTot->getStart())
                ;
            }
            if ($this->datumTot->getEnd()) {
                $builder
                    ->andWhere('schorsing.datumTot <= :datum_tot_end')
                    ->setParameter('datum_tot_end', $this->datumTot->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
