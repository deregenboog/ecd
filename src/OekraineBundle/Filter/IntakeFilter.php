<?php

namespace OekraineBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Bezoeker;
use OekraineBundle\Entity\Locatie;

class IntakeFilter implements FilterInterface
{
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

    /**
     * @var Bezoeker
     */
    public $bezoeker;

    public function applyTo(QueryBuilder $builder)
    {

        if ($this->locatie) {
            $builder
                ->andWhere('intakelocatie = :locatie')
                ->setParameter('locatie', $this->locatie)
            ;
        }

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('intake.intakedatum >= :datum_start')
                    ->setParameter('datum_start', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('intake.intakedatum <= :datum_end')
                    ->setParameter('datum_end', $this->datum->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
