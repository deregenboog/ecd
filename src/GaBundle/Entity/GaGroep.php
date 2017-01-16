<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="GaBundle\Repository\GaGroepRepository")
 * @ORM\Table(name="groepsactiviteiten_groepen")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "ErOpUit" = "GaGroepErOpUit",
 *     "Buurtmaatjes" = "GaGroepBuurtmaatjes",
 *     "Kwartiermaken" = "GaGroepKwartiermaken",
 *     "OpenHuis" = "GaGroepOpenHuis",
 *     "Organisatie" = "GaGroepOrganisatie"
 * })
 */
abstract class GaGroep
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(length=100, nullable=false)
     */
    protected $naam;

    /**
     * @ORM\Column(length=20, nullable=true)
     */
    protected $werkgebied;

    /**
     * @ORM\Column(name="activiteiten_registreren", type="boolean", nullable=true)
     */
    protected $activiteitenRegistreren;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $einddatum;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $modified;

    /**
     * @var GaKlantLidmaatschap[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="GaKlantLidmaatschap", mappedBy="gaGroep")
     */
    protected $gaKlantLeden;

    /**
     * @var GaVrijwilligerLidmaatschap[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="GaVrijwilligerLidmaatschap", mappedBy="gaGroep")
     */
    protected $gaVrijwilligerLeden;

    /**
     * @var GaActiviteit[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="GaActiviteit", mappedBy="gaGroep")
     */
    protected $gaActiviteiten;

    public function __construct()
    {
        $this->gaActiviteiten = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    public function getWerkgebied()
    {
        return $this->werkgebied;
    }

    public function setWerkgebied($werkgebied)
    {
        $this->werkgebied = $werkgebied;

        return $this;
    }

    public function getActiviteitenRegistreren()
    {
        return $this->activiteitenRegistreren;
    }

    public function setActiviteitenRegistreren($activiteitenRegistreren)
    {
        $this->activiteitenRegistreren = $activiteitenRegistreren;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum($startdatum)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum($einddatum)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getGaActiviteiten()
    {
        return $this->gaActiviteiten;
    }
}
