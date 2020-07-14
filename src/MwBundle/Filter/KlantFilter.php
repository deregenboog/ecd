<?php

namespace MwBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use MwBundle\Entity\MwDossierStatus;
use MwBundle\Entity\Verslag;

class KlantFilter implements FilterInterface
{
    /**
     * @var Locatie
     */
    public $gebruikersruimte;

    /**
     * @var Locatie
     */
    public $laatsteIntakeLocatie;

    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Verslag
     */
    public $verslag;

    /**
     * @var AppDateRangeModel
     */
    public $laatsteIntakeDatum;

    /**
     * @var AppDateRangeModel
     */
    public $laatsteVerslagDatum;

    /**
     * @var bool
     */
    public $alleenMetVerslag = true;

    /**
     * @var AppKlantFilter
     */
    public $klant;

    /**
     * @var MwDossierStatus;
     */
    public $huidigeMwStatus;

    public function applyTo(QueryBuilder $builder)
    {
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

        if($this->verslag)
        {
            $builder
                ->andWhere('verslag.medewerker = :medewerker')
                ->setParameter('medewerker',$this->verslag)
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

        if ($this->laatsteVerslagDatum) {
            if ($this->laatsteVerslagDatum->getStart()) {
                $builder
                    ->andHaving('MAX(verslag.datum) >= :laatste_verslag_datum_van')
                    ->setParameter('laatste_verslag_datum_van', $this->laatsteVerslagDatum->getStart())
                ;
            }
            if ($this->laatsteVerslagDatum->getEnd()) {
                $builder
                    ->andHaving('MAX(verslag.datum) <= :laatste_verslag_datum_tot')
                    ->setParameter('laatste_verslag_datum_tot', $this->laatsteVerslagDatum->getEnd())
                ;
            }
        }

        if($this->huidigeMwStatus == 'Aanmelding')
        {
            $builder
                ->andWhere($builder->expr()->isInstanceOf('huidigeMwStatus', Aanmelding::class));
        }
        else if($this->huidigeMwStatus == 'Afsluiting') {
            $builder
                ->andWhere($builder->expr()->isInstanceOf('huidigeMwStatus', Afsluiting::class));
        }

        if ($this->alleenMetVerslag) {
            $builder->andHaving('aantalVerslagen > 0');
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
