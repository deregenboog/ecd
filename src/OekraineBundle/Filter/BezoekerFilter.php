<?php

namespace OekraineBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Aanmelding;
use OekraineBundle\Entity\Locatie;
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
    public $intakelocatie;

    /**
     * @var AppKlantFilter
     */
    public $appKlant;

    /**
     * @var string
     */
    public $huidigeStatus;

    /**
     * @var Locatie
     */
    public $woonlocatie;

    /**
     * @var Medewerker
     */
    public $mentalCoach;

    public function __construct(?StrategyInterface $strategy = null)
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
                ->innerJoin('bezoeker.intake', 'intake')
                ->andWhere('intake.woonlocatie = :woonlocatie')
                ->setParameter('woonlocatie', $this->woonlocatie)
            ;
        }
        if ($this->mentalCoach) {
            $builder
                ->innerJoin('bezoeker.mentalCoach', 'mentalCoach')
                ->andWhere('mentalCoach = :mentalCoach')
                ->setParameter('mentalCoach', $this->mentalCoach)
            ;
        }

        if ($this->appKlant) {
            $this->appKlant->applyTo($builder, 'appKlant');
        }
    }
}
