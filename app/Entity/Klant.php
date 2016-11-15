<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Entity
 * @Table(name="klanten")
 */
class Klant
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(name="MezzoID", type="integer")
     */
    private $mezzoId = 0;

    /**
     * @Column(type="string")
     */
    private $voornaam;

    /**
     * @Column(type="string")
     */
    private $roepnaam;

    /**
     * @Column(type="string", nullable=true)
     */
    private $tussenvoegsel;

    /**
     * @Column(type="string")
     */
    private $achternaam;

    /**
     * @var \DateTime
     * @Column(type="date", nullable=true)
     */
    private $geboortedatum;

    /**
     * @Column(type="string")
     * @Email
     */
    private $email;

    /**
     * @Column(name="BSN", type="string")
     */
    private $bsn = '';

    /**
     * @var Medewerker
     * @ManyToOne(targetEntity="Medewerker")
     */
    private $medewerker;

    /**
     * @var Geslacht
     * @ManyToOne(targetEntity="Geslacht")
     */
    private $geslacht;

    /**
     * @var Land
     * @ManyToOne(targetEntity="Land")
     */
    private $land;

    /**
     * @var Nationaliteit
     * @ManyToOne(targetEntity="Nationaliteit")
     */
    private $nationaliteit;

    /**
     * @Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @Column(type="datetime", nullable=false)
     */
    private $modified;

    /**
     * @Column(name="laatste_TBC_controle", type="date")
     */
    private $laatsteTbcControle;

    /**
     * @Column(name="laste_intake_id", type="integer")
     */
    private $laatsteIntakeId;

    /**
     * @Column(name="laatste_registratie_id", type="integer")
     */
    private $laatsteRegistratieId;

    /**
     * @Column(type="string")
     */
    private $adres;

    /**
     * @Column(type="string")
     */
    private $postcode;

    /**
     * @Column(type="string")
     */
    private $plaats;

    /**
     * @Column(type="string")
     */
    private $werkgebied;

    /**
     * @Column(type="string")
     */
    private $postcodegebied;

    /**
     * @Column(type="string")
     */
    private $mobiel;

    /**
     * @Column(type="string")
     */
    private $telefoon;

    /**
     * @Column(type="text")
     */
    private $opmerking;

    /**
     * @Column(type="boolean")
     */
    private $overleden;

    /**
     * @Column(type="boolean")
     */
    private $disabled;

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

    public function getGeboortedatum()
    {
        return $this->geboortedatum;
    }

    public function setGeboortedatum(\DateTime $geboortedatum = null)
    {
        $this->geboortedatum = $geboortedatum;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }
}
