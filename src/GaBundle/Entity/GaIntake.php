<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_intakes")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({
 *     "Klant" = "GaKlantIntake",
 *     "Vrijwilliger" = "GaVrijwilligerIntake"
 * })
 * @Gedmo\Loggable
 */
abstract class GaIntake
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $gespreksverslag;

    /**
     * @ORM\Column(name="informele_zorg", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $informeleZorg;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $dagbesteding;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $inloophuis;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $hulpverlening;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $ondernemen;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $overdag;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $ontmoeten;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $regelzaken;

    /**
     * @ORM\Column(name="gezin_met_kinderen", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $gezinMetKinderen;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $intakedatum;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $afsluitdatum;

    /**
     * @ORM\ManyToOne(targetEntity="GaAfsluiting")
     * @ORM\JoinColumn(name="groepsactiviteiten_afsluiting_id")
     * @Gedmo\Versioned
     */
    protected $gaAfsluiting;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Medewerker
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function isDeletable()
    {
        return false;
    }

    public function getGespreksverslag()
    {
        return $this->gespreksverslag;
    }

    public function setGespreksverslag($verslag = null)
    {
        $this->gespreksverslag = $verslag;

        return $this;
    }

    public function getIntakedatum()
    {
        return $this->intakedatum;
    }

    public function setIntakedatum(\DateTime $intakedatum)
    {
        $this->intakedatum = $intakedatum;

        return $this;
    }

    public function getGaAfsluiting()
    {
        return $this->gaAfsluiting;
    }

    public function isGezinMetKinderen()
    {
        return $this->gezinMetKinderen;
    }
}
