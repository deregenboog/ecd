<?php

namespace OekraineBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="oekraine_registraties",
 *     indexes={
 *         @ORM\Index(columns={"bezoeker_id"}),
 *         @ORM\Index(columns={"locatie_id", "closed", "binnen_date"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Registratie
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
     * @var Bezoeker
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Bezoeker", inversedBy="registraties")
     * @Gedmo\Versioned
     */
    private $bezoeker;

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
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $aantalSpuiten = 0;

    /**
     * @var int
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $closed = false;

    public function __construct(Bezoeker $bezoeker, Locatie $locatie)
    {
        $this->setBezoeker($bezoeker);
        $this->setLocatie($locatie);
        $this->binnen = new \DateTime();
    }

    public function __toString()
    {
        $parts = [
            (string) $this->bezoeker,
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

    /**
     * @return Bezoeker
     */
    public function getBezoeker(): Bezoeker
    {
        return $this->bezoeker;
    }

    /**
     * @param Bezoeker $bezoeker
     * @return Registratie
     */
    public function setBezoeker(Bezoeker $bezoeker): Registratie
    {
        $this->bezoeker = $bezoeker;
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

    /**
     * @return int
     */
    public function getAantalSpuiten(): ?int
    {
        return $this->aantalSpuiten;
    }

    /**
     * @param int $aantalSpuiten
     */
    public function setAantalSpuiten(int $aantalSpuiten): void
    {
        $this->aantalSpuiten = $aantalSpuiten;
    }


}
