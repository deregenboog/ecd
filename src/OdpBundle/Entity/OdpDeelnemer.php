<?php

namespace OdpBundle\Entity;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;
use AppBundle\Entity\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_deelnemers")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({"Huurder" = "OdpHuurder", "Verhuurder" = "OdpVerhuurder"})
 * @ORM\HasLifecycleCallbacks
 */
abstract class OdpDeelnemer
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var OdpIntake
     * @ORM\OneToOne(targetEntity="OdpIntake", mappedBy="odpDeelnemer")
     */
    protected $odpIntake;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $medewerker;

    /**
     * @ORM\Column(name="aanmelddatum", type="date")
     */
    protected $aanmelddatum;

    /**
     * @ORM\Column(name="afsluitdatum", type="date", nullable=true)
     */
    protected $afsluitdatum;

    /**
     * @var OdpDeelnemerAfsluiting
     * @ORM\ManyToOne(targetEntity="OdpDeelnemerAfsluiting")
     */
    protected $odpDeelnemerAfsluiting;

    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     */
    protected $klant;

    public function __construct()
    {
        $this->odpHuurovereenkomsten = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->klant;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getOdpDeelnemerAfsluiting()
    {
        return $this->odpDeelnemerAfsluiting;
    }

    public function setOdpDeelnemerAfsluiting(OdpDeelnemerAfsluiting $odpDeelnemerAfsluiting)
    {
        $this->odpDeelnemerAfsluiting = $odpDeelnemerAfsluiting;

        return $this;
    }

    public function getOdpIntake()
    {
        return $this->odpIntake;
    }

    public function getAanmelddatum()
    {
        return $this->aanmelddatum;
    }

    public function setAanmelddatum($aanmelddatum)
    {
        $this->aanmelddatum = $aanmelddatum;

        return $this;
    }

    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum($afsluitdatum)
    {
        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }
}
