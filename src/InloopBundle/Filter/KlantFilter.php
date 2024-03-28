<?php

namespace InloopBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Toegang;

class KlantFilter implements FilterInterface
{
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
     * @var bool
     */
    public $nuBinnen;

    public function applyTo(QueryBuilder $builder)
    {
        $builder
            ->addSelect('schorsing')
            ->leftJoin('klant.schorsingen', 'schorsing')
        ;

        if ($this->locatie) {
            $builder
                ->innerJoin(Toegang::class, 'toegang', 'WITH', 'toegang.klant = klant AND toegang.locatie = :locatie')
                ->setParameter('locatie', $this->locatie)
            ;
        }

        if ($this->id) {
            $builder
                ->andWhere('klant.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->gebruikersruimte) {
            $builder
                ->andWhere('laatsteIntake.gebruikersruimte = :gebruikersruimte')
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

        if ($this->huidigeStatus) {
            $builder->andWhere($builder->expr()->isInstanceOf('status', $this->huidigeStatus));
        }

        if (true === $this->nuBinnen) {
            $builder->join('klant.laatsteRegistratie', 'laatsteRegistratie', 'WITH', 'laatsteRegistratie.closed = false');
        } elseif (false === $this->nuBinnen) {
            $builder
                ->leftJoin('klant.laatsteRegistratie', 'laatsteRegistratie', 'WITH', 'laatsteRegistratie.closed = false')
                ->andWhere('laatsteRegistratie IS NULL')
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
