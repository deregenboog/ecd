<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @Entity
 * @Table(name="hs_klanten")
 */
class HsKlant
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="date")
     */
    private $inschrijving;

    /**
     * @Column(type="date")
     */
    private $uitschrijving;

    /**
     * @Column(type="date")
     */
    private $laatsteContact;

    /**
     * @Column(type="datetime")
     */
    private $created;

    /**
     * @Column(type="datetime")
     */
    private $modified;

    /**
     * @var Klant
     * @OneToOne(targetEntity="Klant")
     * @JoinColumn(nullable=false)
     */
    private $klant;

    /**
     * @var ArrayCollection|HsKlus[]
     * @OneToMany(targetEntity="HsKlus", mappedBy="hsKlant")
     */
    private $hsKlussen;

    /**
     * @var HsProfiel[]
     * @OneToMany(targetEntity="HsProfielCode", mappedBy="hsKlant", cascade={"persist", "remove"})
     */
    private $hsProfielCodes;

    public function __construct()
    {
        $this->hsProfielCodes = new ArrayCollection();
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

    public function getHsProfielCodes()
    {
        return $this->hsProfielCodes;
    }

    public function addHsProfielCode(HsProfielCode $hsProfielCode)
    {
        $this->hsProfielCodes->add($hsProfielCode);
        $hsProfielCode->setHsKlant($this);

        return $this;
    }
}
