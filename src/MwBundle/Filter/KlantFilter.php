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
    public $laatsteVerslagDatum;

    /**
     * @var AppKlantFilter
     */
    public $klant;

    /**
     * @var bool
     */
    public $isGezin;

    /**
     * @var Project
     */
    public $project;

    public $huidigeMwStatus = 'Aanmelding';

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->gebruikersruimte) {
            $builder
                ->andWhere('gebruikersruimte = :gebruikersruimte')
                ->setParameter('gebruikersruimte', $this->gebruikersruimte)
            ;
        }

        if ($this->laatsteVerslagLocatie) {
            $builder
                ->andWhere('locatie = :locatie')
                ->setParameter('locatie', $this->laatsteVerslagLocatie)
            ;
        }

        if ($this->maatschappelijkWerker) {
            $builder
                ->andWhere('maatschappelijkWerker = :maatschappelijkWerker
                    OR laatsteVerslag.medewerker = :maatschappelijkWerker
                    OR huidigeMwStatus.medewerker = :maatschappelijkWerker')
                ->setParameter('maatschappelijkWerker', $this->maatschappelijkWerker)
            ;
        }

        if ($this->laatsteVerslagDatum) {
            if ($this->laatsteVerslagDatum->getStart()) {
                $builder
                    ->andWhere('laatsteVerslag.datum >= :laatste_verslag_datum_van')
                    ->setParameter('laatste_verslag_datum_van', $this->laatsteVerslagDatum->getStart())
                ;
            }
            if ($this->laatsteVerslagDatum->getEnd()) {
                $builder
                    ->andWhere('laatsteVerslag.datum <= :laatste_verslag_datum_tot')
                    ->setParameter('laatste_verslag_datum_tot', $this->laatsteVerslagDatum->getEnd())
                ;
            }
        }

        switch ($this->huidigeMwStatus) {
            case 'Aanmelding':
                $builder->andWhere($builder->expr()->isInstanceOf('huidigeMwStatus', Aanmelding::class));
                break;
            case 'Afsluiting':
                $builder->andWhere($builder->expr()->isInstanceOf('huidigeMwStatus', Afsluiting::class));
                break;
        }

        if (!is_null($this->isGezin)) {
            $builder->andWhere('info.isGezin = :is_gezin')->setParameter('is_gezin', $this->isGezin);
        }

        if ($this->project) {
            $builder->andWhere('project = :project')->setParameter('project', $this->project);
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
