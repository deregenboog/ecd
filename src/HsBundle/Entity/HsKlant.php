<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_klanten")
 * @ORM\HasLifecycleCallbacks
 */
class HsKlant
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
    private $inschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $uitschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $laatsteContact;

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
     * @var ArrayCollection|HsKlus[]
     * @ORM\OneToMany(targetEntity="HsKlus", mappedBy="hsKlant")
     */
    private $hsKlussen;

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
        $this->hsKlussen = new ArrayCollection();
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

    public function getInschrijving()
    {
        return $this->inschrijving;
    }

    public function setInschrijving(\DateTime $inschrijving)
    {
        $this->inschrijving = $inschrijving;

        return $this;
    }

    public function getHsKlussen()
    {
        return $this->hsKlussen;
    }

    public function isDeletable()
    {
        return count($this->hsKlussen) === 0;
    }
}
