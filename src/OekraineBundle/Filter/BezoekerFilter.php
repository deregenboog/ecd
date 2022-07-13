<?php

namespace OekraineBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Aanmelding;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Entity\Toegang;
use OekraineBundle\Strategy\StrategyInterface;

class BezoekerFilter implements FilterInterface
{
    /**
     * @var StrategyInterface
     */
    public $strategy;

    /**
     * @var int
     */
    public $id;

    /**
     * @var Locatie
     */
    public $locatie;

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

    /**
     * @var string
     */
    public $huidigeStatus;

    /**
     * @var Locatie
     */
    public $woonlocatie;

    public function __construct(StrategyInterface $strategy = null)
    {
            $this->strategy = $strategy;
//            $this->huidigeStatus = Aanmelding::class;
    }

    public function applyTo(QueryBuilder $builder)
    {

//
//        if ($this->laatsteIntakeLocatie) {
//            $builder
//                ->andWhere('laatsteIntake.intakelocatie = :laatste_intake_locatie')
//                ->setParameter('laatste_intake_locatie', $this->laatsteIntakeLocatie)
//            ;
//        }
//
//        if ($this->laatsteIntakeDatum) {
//            if ($this->laatsteIntakeDatum->getStart()) {
//                $builder
//                    ->andWhere('laatsteIntake.intakedatum >= :laatste_intake_datum_van')
//                    ->setParameter('laatste_intake_datum_van', $this->laatsteIntakeDatum->getStart())
//                ;
//            }
//            if ($this->laatsteIntakeDatum->getEnd()) {
//                $builder
//                    ->andWhere('laatsteIntake.intakedatum <= :laatste_intake_datum_tot')
//                    ->setParameter('laatste_intake_datum_tot', $this->laatsteIntakeDatum->getEnd())
//                ;
//            }
//        }

//        if ($this->huidigeStatus) {
//            $builder->andWhere($builder->expr()->isInstanceOf('status', $this->huidigeStatus));
//        }

        if ($this->woonlocatie) {
            $builder
                ->innerJoin("bezoeker.intake","intake")
                ->andWhere('intake.woonlocatie = :woonlocatie')
                ->setParameter('woonlocatie', $this->woonlocatie)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
