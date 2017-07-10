<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Entity\Klant;

/**
 * @ORM\Entity
 * @ORM\Table(name="registraties")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Registratie
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Locatie
     * @ORM\ManyToOne(targetEntity="Locatie", inversedBy="registraties")
     * @Gedmo\Versioned
     */
    private $locatie;

    /**
     * @var Klant
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="registraties")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Versioned
     */
    private $binnen;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $buiten;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $douche;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $mw;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $gbrv;

    /**
     * @var int
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $kleding = false;

    /**
     * @var int
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $maaltijd = false;

    /**
     * @var int
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $activering = false;

    /**
     * @var int
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $closed = false;

    public function getId()
    {
        return $this->id;
    }

    public function getLocatie()
    {
        return $this->locatie;
    }

    public function setLocatie(Locatie $locatie)
    {
        $this->locatie = $locatie;

        return $this;
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

    public function getBinnen()
    {
        return $this->binnen;
    }

    public function setBinnen(\DateTime $binnen)
    {
        $this->binnen = $binnen;

        return $this;
    }

    public function getBuiten()
    {
        return $this->buiten;
    }

    public function setBuiten(\DateTime $buiten)
    {
        $this->buiten = $buiten;

        return $this;
    }

    public function getDouche()
    {
        return $this->douche;
    }

    public function setDouche($douche)
    {
        $this->douche = $douche;

        return $this;
    }

    public function getMw()
    {
        return $this->mw;
    }

    public function setMw($mw)
    {
        $this->mw = $mw;

        return $this;
    }

    public function getGbrv()
    {
        return $this->gbrv;
    }

    public function setGbrv($gbrv)
    {
        $this->gbrv = $gbrv;

        return $this;
    }

    public function isKleding()
    {
        return $this->kleding;
    }

    public function setKleding($kleding)
    {
        $this->kleding = $kleding;

        return $this;
    }

    public function isMaaltijd()
    {
        return $this->maaltijd;
    }

    public function setMaaltijd($maaltijd)
    {
        $this->maaltijd = $maaltijd;

        return $this;
    }

    public function isActivering()
    {
        return $this->activering;
    }

    public function setActivering($activering)
    {
        $this->activering = $activering;

        return $this;
    }

    public function getClosed()
    {
        return $this->closed;
    }

    public function isClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }
}
