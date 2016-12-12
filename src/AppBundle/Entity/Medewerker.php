<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="medewerkers")
 */
class Medewerker
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $voornaam;

    /**
     * @Column(type="string", nullable=true)
     */
    private $tussenvoegsel;

    /**
     * @Column(type="string")
     */
    private $achternaam;

    /**
     * @Column(name="groups", type="simple_array", nullable=true)
     */
    private $groepen = [];

    public function __construct()
    {
        $this->klanten = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNaam();
    }

    public function getNaam()
    {
        return implode(' ', [$this->voornaam, $this->tussenvoegsel, $this->achternaam]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getVoornaam()
    {
        return $this->voornaam;
    }

    public function setVoornaam($voornaam)
    {
        $this->voornaam = $voornaam;

        return $this;
    }

    public function getTussenvoegsel()
    {
        return $this->tussenvoegsel;
    }

    public function setTussenvoegsel($tussenvoegsel)
    {
        $this->tussenvoegsel = $tussenvoegsel;

        return $this;
    }

    public function getAchternaam()
    {
        return $this->achternaam;
    }

    public function setAchternaam($achternaam)
    {
        $this->achternaam = $achternaam;

        return $this;
    }

    public function getGroepen()
    {
        return $this->groepen;
    }

    public function setGroepen(array $groepen = [])
    {
        $this->groepen = $groepen;

        return $this;
    }

}
