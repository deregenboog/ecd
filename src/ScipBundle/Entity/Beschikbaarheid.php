<?php

namespace ScipBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="scip_beschikbaarheid",
 *     indexes={}
 * )
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Beschikbaarheid
{
    use IdentifiableTrait;

    /**
     * @var Deelname
     *
     * @ORM\OneToOne(targetEntity="Deelname", inversedBy="beschibaarheid")
     *
     * @Gedmo\Versioned
     */
    private $deelname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $maandagVan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $maandagTot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $dinsdagVan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $dinsdagTot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $woensdagVan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $woensdagTot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $donderdagVan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $donderdagTot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $vrijdagVan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $vrijdagTot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $zaterdagVan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $zaterdagTot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $zondagVan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $zondagTot;

    public function __construct(?Deelname $deelname = null)
    {
        $this->deelname = $deelname;
    }

    /**
     * @return Deelname
     */
    public function getDeelname()
    {
        return $this->deelname;
    }

    public function setDeelname(Deelname $deelname)
    {
        $this->deelname = $deelname;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMaandagVan()
    {
        return $this->maandagVan;
    }

    /**
     * @param \DateTime $maandagVan
     */
    public function setMaandagVan($maandagVan)
    {
        $this->maandagVan = $maandagVan;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMaandagTot()
    {
        return $this->maandagTot;
    }

    /**
     * @param \DateTime $maandagTot
     */
    public function setMaandagTot($maandagTot)
    {
        $this->maandagTot = $maandagTot;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDinsdagVan()
    {
        return $this->dinsdagVan;
    }

    /**
     * @param \DateTime $dinsdagVan
     */
    public function setDinsdagVan($dinsdagVan)
    {
        $this->dinsdagVan = $dinsdagVan;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDinsdagTot()
    {
        return $this->dinsdagTot;
    }

    /**
     * @param \DateTime $dinsdagTot
     */
    public function setDinsdagTot($dinsdagTot)
    {
        $this->dinsdagTot = $dinsdagTot;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getWoensdagVan()
    {
        return $this->woensdagVan;
    }

    /**
     * @param \DateTime $woensdagVan
     */
    public function setWoensdagVan($woensdagVan)
    {
        $this->woensdagVan = $woensdagVan;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getWoensdagTot()
    {
        return $this->woensdagTot;
    }

    /**
     * @param \DateTime $woensdagTot
     */
    public function setWoensdagTot($woensdagTot)
    {
        $this->woensdagTot = $woensdagTot;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDonderdagVan()
    {
        return $this->donderdagVan;
    }

    /**
     * @param \DateTime $donderdagVan
     */
    public function setDonderdagVan($donderdagVan)
    {
        $this->donderdagVan = $donderdagVan;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDonderdagTot()
    {
        return $this->donderdagTot;
    }

    /**
     * @param \DateTime $donderdagTot
     */
    public function setDonderdagTot($donderdagTot)
    {
        $this->donderdagTot = $donderdagTot;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getVrijdagVan()
    {
        return $this->vrijdagVan;
    }

    /**
     * @param \DateTime $vrijdagVan
     */
    public function setVrijdagVan($vrijdagVan)
    {
        $this->vrijdagVan = $vrijdagVan;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getVrijdagTot()
    {
        return $this->vrijdagTot;
    }

    /**
     * @param \DateTime $vrijdagTot
     */
    public function setVrijdagTot($vrijdagTot)
    {
        $this->vrijdagTot = $vrijdagTot;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getZaterdagVan()
    {
        return $this->zaterdagVan;
    }

    /**
     * @param \DateTime $zaterdagVan
     */
    public function setZaterdagVan($zaterdagVan)
    {
        $this->zaterdagVan = $zaterdagVan;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getZaterdagTot()
    {
        return $this->zaterdagTot;
    }

    /**
     * @param \DateTime $zaterdagTot
     */
    public function setZaterdagTot($zaterdagTot)
    {
        $this->zaterdagTot = $zaterdagTot;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getZondagVan()
    {
        return $this->zondagVan;
    }

    /**
     * @param \DateTime $zondagVan
     */
    public function setZondagVan($zondagVan)
    {
        $this->zondagVan = $zondagVan;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getZondagTot()
    {
        return $this->zondagTot;
    }

    /**
     * @param \DateTime $zondagTot
     */
    public function setZondagTot($zondagTot)
    {
        $this->zondagTot = $zondagTot;

        return $this;
    }
}
