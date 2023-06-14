<?php

namespace MwBundle\Filter;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Werkgebied;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Locatie;
use MwBundle\Entity\Verslag;

class WachtlijstFilter implements FilterInterface
{
    /**
     * @var Werkgebied
     */
    public $werkgebied;

    /**
     * @var Locatie
     */
    public $locatie;

    /**
     * @var AppDateRangeModel
     */
    public $datum;

    /**
     * @var AppKlantFilter
     */
    public $klant;


    public function applyTo(QueryBuilder $builder)
    {

        $fromClass = $builder->getDQLPart("from")[0]->getFrom();

        switch($fromClass) {
            case Verslag::class:
                $this->applyVerslag($builder);
                break;
            case Klant::class:

                $this->applyIntake($builder);
                break;
        }


//        parent::applyTo($builder);

    }

    private function applyVerslag(QueryBuilder $builder): void
    {
        if ($this->locatie) {
            $builder
                ->andWhere('locatie = :locatie')
                ->setParameter('locatie', $this->locatie)
            ;
        }

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('verslag.datum >= :datum_start')
                    ->setParameter('datum_start', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('verslag.datum <= :datum_end')
                    ->setParameter('datum_end', $this->datum->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->werkgebied) {
            $builder
                ->andWhere('werkgebied = :werkgebied')
                ->setParameter('werkgebied', $this->werkgebied)
            ;
        }

    }

    private function applyIntake(QueryBuilder $builder): void
    {
        if ($this->locatie) {
            $builder
                ->andWhere('(intakelocatie = :locatie OR intakelocatie IS NULL)')
                ->setParameter('locatie', $this->locatie)
            ;
        }

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('intake.intakedatum >= :datum_start OR intake.intakedatum IS NULL')
                    ->setParameter('datum_start', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('intake.intakedatum <= :datum_end OR intake.intakedatum IS NULL')
                    ->setParameter('datum_end', $this->datum->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->werkgebied) {
            $builder
                ->andWhere('werkgebied = :werkgebied OR werkgebied IS NULL')
                ->setParameter('werkgebied', $this->werkgebied)
            ;
        }

    }
}
