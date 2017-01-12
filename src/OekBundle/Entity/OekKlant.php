<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_klanten")
 * @ORM\HasLifecycleCallbacks
 */
class OekKlant
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $aanmelding;

    /**
     * @ORM\Column(name="verwijzing_door", nullable=false)
     */
    private $verwijzingDoor;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $afsluiting;

    /**
     * @ORM\Column(name="verwijzing_naar", nullable=false)
     */
    private $verwijzingNaar;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $modified;

    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $klant;

    /**
     * @var ArrayCollection|OekTraining[]
     * @ORM\OneToMany(targetEntity="OekTraining", mappedBy="oekKlant")
     */
    private $oekTrainingen;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = $this->modified = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->modified = new \DateTime();
    }

    public function __construct()
    {
        $this->oekTrainingen = new ArrayCollection();
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

    public function getHsKlussen()
    {
        return $this->hsKlussen;
    }

    public function getAanmelding()
    {
        return $this->aanmelding;
    }

    public function setAanmelding($aanmelding)
    {
        $this->aanmelding = $aanmelding;
        return $this;
    }

    public function getVerwijzingDoor()
    {
        return $this->verwijzingDoor;
    }

    public function setVerwijzingDoor($verwijzingDoor)
    {
        $this->verwijzingDoor = $verwijzingDoor;
        return $this;
    }

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting($afsluiting)
    {
        $this->afsluiting = $afsluiting;
        return $this;
    }

    public function getVerwijzingNaar()
    {
        return $this->verwijzingNaar;
    }

    public function setVerwijzingNaar($verwijzingNaar)
    {
        $this->verwijzingNaar = $verwijzingNaar;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function getOekTrainingen()
    {
        return $this->oekTrainingen;
    }

    public function addOekTraining(OekTraining $oekTraining)
    {
        $this->oekTrainingen->add($oekTraining);

        return $this;
    }

    public function removeOekTraining(OekTraining $oekTraining)
    {
        $this->oekTrainingen->remove($oekTraining);

        return $this;
    }
}
