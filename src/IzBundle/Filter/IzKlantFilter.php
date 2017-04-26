<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;

class IzKlantFilter implements FilterInterface
{
    /**
     * @var AppDateRangeModel
     */
    public $afsluitDatum;

    /**
     * @var boolean
     */
    public $openDossiers;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var IzProject
     */
    public $izProject;

    /**
     * @var Medewerker
     */
    public $medewerker;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->afsluitDatum) {
            if ($this->afsluitDatum->getStart()) {
                $builder
                    ->andWhere('izKlant.afsluitDatum >= :izKlant_afsluitDatum_van')
                    ->setParameter('izKlant_afsluitDatum_van', $this->afsluitDatum->getStart())
                ;
            }
            if ($this->afsluitDatum->getEnd()) {
                $builder
                    ->andWhere('izKlant.afsluitDatum <= :izKlant_afsluitDatum_tot')
                    ->setParameter('izKlant_afsluitDatum_tot', $this->afsluitDatum->getEnd())
                ;
            }
        }

        if ($this->openDossiers) {
            $builder
                ->andWhere('izKlant.afsluitDatum IS NULL OR izKlant.afsluitDatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->izProject) {
            $builder
                ->andWhere('izHulpvraag.izProject = :izProject')
                ->setParameter('izProject', $this->izProject)
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('izHulpvraag.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }
    }
}
