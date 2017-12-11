<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\VrijwilligerFilter;

class IzKoppelingFilter implements FilterInterface
{
    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    /**
     * @var \DateTime
     */
    public $koppelingStartdatum;

    /**
     * @var \DateTime
     */
    public $koppelingEinddatum;

    /**
     * @var bool
     */
    public $lopendeKoppelingen;

    /**
     * @var IzProject
     */
    public $izProject;

    /**
     * @var Medewerker
     */
    public $izHulpvraagMedewerker;

    /**
     * @var Medewerker
     */
    public $izHulpaanbodMedewerker;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }

        if ($this->koppelingStartdatum) {
            $builder
                ->andWhere('izHulpvraag.koppelingStartdatum = :koppelingStartdatum')
                ->setParameter('koppelingStartdatum', $this->koppelingStartdatum)
            ;
        }

        if ($this->koppelingEinddatum) {
            $builder
                ->andWhere('izHulpvraag.koppelingEinddatum = :koppelingEinddatum')
                ->setParameter('koppelingEinddatum', $this->koppelingEinddatum)
            ;
        }

        if ($this->lopendeKoppelingen) {
            $builder
                ->andWhere('izHulpvraag.koppelingEinddatum IS NULL OR izHulpvraag.koppelingEinddatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->izProject) {
            $builder
                ->andWhere('izHulpvraag.izProject = :izProject')
                ->setParameter('izProject', $this->izProject)
            ;
        }

        if ($this->izHulpvraagMedewerker) {
            $builder
                ->andWhere('izHulpvraag.medewerker = :izHulpvraagMedewerker')
                ->setParameter('izHulpvraagMedewerker', $this->izHulpvraagMedewerker)
            ;
        }

        if ($this->izHulpaanbodMedewerker) {
            $builder
                ->andWhere('izHulpaanbod.medewerker = :izHulpaanbodMedewerker')
                ->setParameter('izHulpaanbodMedewerker', $this->izHulpaanbodMedewerker)
            ;
        }
    }
}
