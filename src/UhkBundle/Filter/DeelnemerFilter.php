<?php

namespace UhkBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\MedewerkerFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use UhkBundle\Entity\Deelnemer;

class DeelnemerFilter implements FilterInterface
{
    public $alias = 'deelnemer';

    /**
     * @var \AppBundle\Filter\KlantFilter
     */
    public $klant;

    /**
     * @var AppDateRangeModel
     */
    public $aanmelddatum;

    /**
     * @var Deelnemer
     */
    public $deelnemer;

    /**
     * @var MedewerkerFilter
     */
    public $medewerker;

    /**
     * @var bool
     */
    public $actief = true;

    /**
     * @var AppDateRangeModel
     */
    public $verslagDatum;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->deelnemer) {
            $this->deelnemer->applyTo($builder);
        }
        if ($this->medewerker) {
            if ($this->medewerker) {
                $builder
                    ->andWhere('deelnemer.medewerker = :medewerker')
                    ->setParameter('medewerker', $this->medewerker)
                ;
            }
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere('deelnemer.aanmelddatum >= :aanmelddatum_start')
                    ->setParameter('aanmelddatum_start', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere('deelnemer.aanmelddatum <= :aanmelddatum_end')
                    ->setParameter('aanmelddatum_end', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if ($this->actief) {
            $builder->andWhere('deelnemer.actief = :actief')
                ->setParameter('actief', $this->actief);
        }
    }
}
