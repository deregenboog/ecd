<?php

namespace InloopBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\RecenteRegistratie;

class RegistratieHistoryFilter extends RegistratieFilter
{
    public function __construct(Locatie $locatie)
    {
        $this->locatie = $locatie;
        $this->binnen = new AppDateRangeModel(new \DateTime('-1 week'));
    }

    public function applyTo(QueryBuilder $builder)
    {
        $builder->innerJoin(RecenteRegistratie::class, 'recenteRegistratie', 'WITH', 'registratie = recenteRegistratie.registratie');

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
