<?php

namespace InloopBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Locatie;
use AppBundle\Form\Model\AppDateRangeModel;

class KlantFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var Locatie
     */
    public $gebruikersruimte;

    /**
     * @var Locatie
     */
    public $laatsteIntakeLocatie;

    /**
     * @var AppDateRangeModel
     */
    public $laatsteIntakeDatum;

    /**
     * @var AppKlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('klant.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->gebruikersruimte) {
            $builder
                ->andWhere('klant.gebruikersruimte = :gebruikersruimte')
                ->setParameter('gebruikersruimte', $this->gebruikersruimte)
            ;
        }

        if ($this->laatsteIntakeLocatie) {
            $builder
                ->andWhere('laatsteIntake.intakelocatie = :laatste_intake_locatie')
                ->setParameter('laatste_intake_locatie', $this->laatsteIntakeLocatie)
            ;
        }

        if ($this->laatsteIntakeDatum) {
            if ($this->laatsteIntakeDatum->getStart()) {
                $builder
                    ->andWhere('laatsteIntake.intakedatum >= :laatste_intake_datum_van')
                    ->setParameter('laatste_intake_datum_van', $this->laatsteIntakeDatum->getStart())
                ;
            }
            if ($this->laatsteIntakeDatum->getEnd()) {
                $builder
                    ->andWhere('laatsteIntake.intakedatum <= :laatste_intake_datum_tot')
                    ->setParameter('laatste_intake_datum_tot', $this->laatsteIntakeDatum->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
