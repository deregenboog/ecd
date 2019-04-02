<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="registraties_2011",
 *     indexes={
 *         @ORM\Index(columns={"klant_id"}),
 *         @ORM\Index(columns={"locatie_id", "closed", "binnen_date"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Registratie2011
{
    use TimestampableTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Locatie
     * @ORM\ManyToOne(targetEntity="Locatie")
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
     * Used for indexing for improved performance.
     *
     * @var \DateTime
     * @ORM\Column(name="binnen_date", type="date", nullable=true)
     */
    private $binnenDate;

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
    private $douche = 0;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $mw = 0;

    /**
     * @deprecated
     *
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     */
    private $gbrv = 0;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $kleding = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $maaltijd = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $activering = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $veegploeg = false;

    /**
     * @var int
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $closed = false;

    public function __construct(Klant $klant, Locatie $locatie)
    {
        $this->setKlant($klant);
        $this->setLocatie($locatie);
        $this->binnen = new \DateTime();
    }

    public function __toString()
    {
        $parts = [
            (string) $this->klant,
            (string) $this->locatie,
            $this->binnen->format('d-m-Y H:i'),
        ];

        if ($this->buiten) {
            $parts[] = $this->buiten->format('- H:i');
        }

        return implode(' ', $parts);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLocatie()
    {
        return $this->locatie;
    }

    private function setLocatie(Locatie $locatie)
    {
        $this->locatie = $locatie;

        return $this;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    private function setKlant(Klant $klant)
    {
        $this->klant = $klant;
//         $klant->setLaatsteRegistratie($this);

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
        $this->closed = true;

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

    public function isVeegploeg()
    {
        return $this->veegploeg;
    }

    public function setVeegploeg($veegploeg)
    {
        $this->veegploeg = (bool) $veegploeg;

        return $this;
    }

    public function isKleding()
    {
        return $this->kleding;
    }

    public function setKleding($kleding)
    {
        $this->kleding = (bool) $kleding;

        return $this;
    }

    public function isMaaltijd()
    {
        return $this->maaltijd;
    }

    public function setMaaltijd($maaltijd)
    {
        $this->maaltijd = (bool) $maaltijd;

        return $this;
    }

    public function isActivering()
    {
        return $this->activering;
    }

    public function setActivering($activering)
    {
        $this->activering = (bool) $activering;

        return $this;
    }

    public function isClosed()
    {
        return $this->closed;
    }

    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }
}
