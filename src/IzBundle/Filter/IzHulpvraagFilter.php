<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;

class IzHulpvraagFilter
{
    /**
     * @var \DateTime
     */
    public $startdatum;

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
        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->startdatum) {
            $builder
                ->andWhere('izHulpvraag.startdatum = :startdatum')
                ->setParameter('startdatum', $this->startdatum)
            ;
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
