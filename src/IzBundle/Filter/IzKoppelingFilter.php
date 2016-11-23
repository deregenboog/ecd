<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;

class IzKoppelingFilter
{
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
     * @var string
     */
    public $klantNaam;

    /**
     * @var string
     */
    public $vrijwilligerNaam;

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

    /**
     * @var string
     */
    public $stadsdeel;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->klantNaam) {
            $builder
            ->andWhere('CONCAT(klant.voornaam, klant.roepnaam, klant.tussenvoegsel, klant.achternaam) LIKE :klantNaam')
            ->setParameter('klantNaam', "%{$this->klantNaam}%")
            ;
        }

        if ($this->vrijwilligerNaam) {
            $builder
            ->andWhere('CONCAT(vrijwilliger.voornaam, vrijwilliger.roepnaam, vrijwilliger.tussenvoegsel, vrijwilliger.achternaam) LIKE :vrijwilligerNaam')
            ->setParameter('vrijwilligerNaam', "%{$this->vrijwilligerNaam}%")
            ;
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

        if (isset($this->stadsdeel['stadsdeel'])) {
            $builder
                ->andWhere('klant.werkgebied = :stadsdeel')
                ->setParameter('stadsdeel', $this->stadsdeel['stadsdeel'])
            ;
        }
    }
}
