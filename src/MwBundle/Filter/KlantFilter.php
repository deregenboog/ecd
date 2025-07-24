<?php

namespace MwBundle\Filter;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use MwBundle\Entity\MwDossierStatus;
use MwBundle\Entity\Project;
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
    public $laatsteVerslagLocatie;

    /**
     * @var Medewerker
     */
    public $maatschappelijkWerker;

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
     * @var AppKlantFilter
     */
    public $klant;

    /**
     * @var bool
     */
    public $isGezin;

    /** @var Project */
    public $project;

    /**
     * @var MwDossierStatus;
     */
    public $huidigeMwStatus = 'Aanmelding';

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->gebruikersruimte) {
            $builder
                ->andWhere('laatsteIntake.gebruikersruimte = :gebruikersruimte')
                ->setParameter('gebruikersruimte', $this->gebruikersruimte)
            ;
        }

        if ($this->laatsteVerslagLocatie) {
            $builder
                ->andWhere('verslag.locatie = :locatie')
                ->setParameter('locatie', $this->laatsteVerslagLocatie)
            ;
        }

        //        if($this->verslag)
        //        {
        //            $builder
        //                ->andWhere('verslag.medewerker = :medewerker')
        //                ->setParameter('medewerker',$this->verslag)
        //                ;
        //
        //        }
        if ($this->maatschappelijkWerker) {
            $builder
                ->andWhere('maatschappelijkWerker = :maatschappelijkWerker')

                ->setParameter('maatschappelijkWerker', $this->maatschappelijkWerker)
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

        if ('Aanmelding' == $this->huidigeMwStatus) {
            $builder
                ->andWhere($builder->expr()->isInstanceOf('huidigeMwStatus', Aanmelding::class));
        } elseif ('Afsluiting' == $this->huidigeMwStatus) {
            $builder
                ->andWhere($builder->expr()->isInstanceOf('huidigeMwStatus', Afsluiting::class));
        }
        if ('1' == $this->isGezin) {
            $builder
                ->leftJoin('klant.info', 'info')
                ->andWhere('info.isGezin = 1');
        } elseif ('0' == $this->isGezin) {
            $builder
                ->leftJoin('klant.info', 'info')
                ->andWhere('info.isGezin = 0');
        } elseif ('null' == $this->isGezin) {
            $builder
                ->leftJoin('klant.info', 'info')
                ->andWhere('info.isGezin = 0 OR info.isGezin IS NULL or info.isGezin =1 ');
        }

        if ($this->project) {
            $builder
                ->andWhere('project = :project')
                ->setParameter('project', $this->project);
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        //        $sql = SqlExtractor::getFullSQL($builder->getQuery());
    }

    public function isDirty()
    {
        $isDirty = false;
        $r = new \ReflectionClass($this);
        $d = $r->getDefaultProperties();
        foreach ($r->getProperties() as $prop) {
            if ($prop->getValue($this) != $d[$prop->getName()]) {
                return true;
            }
        }

        return $isDirty;
    }
}
