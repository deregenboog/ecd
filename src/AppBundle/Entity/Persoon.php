<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 */
class Persoon
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $voornaam;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $roepnaam;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $tussenvoegsel;

    /**
     * @ORM\Column(type="string")
     */
    protected $achternaam;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    protected $geboortedatum;

    /**
     * @ORM\Column(type="string")
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\Column(name="BSN", type="string")
     */
    protected $bsn = '';

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="Medewerker")
     */
    protected $medewerker;

    /**
     * @var Geslacht
     * @ORM\ManyToOne(targetEntity="Geslacht")
     */
    protected $geslacht;

    /**
     * @var Land
     * @ORM\ManyToOne(targetEntity="Land")
     */
    protected $land;

    /**
     * @var Nationaliteit
     * @ORM\ManyToOne(targetEntity="Nationaliteit")
     */
    protected $nationaliteit;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $modified;

    /**
     * @ORM\Column(type="string")
     */
    protected $adres;

    /**
     * @ORM\Column(type="string")
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string")
     */
    protected $plaats;

    /**
     * @ORM\Column(type="string")
     */
    protected $werkgebied;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $postcodegebied;

    /**
     * @ORM\Column(type="string")
     */
    protected $mobiel;

    /**
     * @ORM\Column(type="string")
     */
    protected $telefoon;

    /**
     * @ORM\Column(name="geen_post", type="boolean")
     */
    protected $geenPost;

    /**
     * @ORM\Column(name="geen_email", type="boolean")
     */
    protected $geenEmail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $opmerking;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $disabled;

    public function __toString()
    {
        return $this->getNaam();
    }

    public function getNaam()
    {
        $parts = [];

        if ($this->voornaam) {
            $parts[] = $this->voornaam;
        }
        if ($this->roepnaam) {
            $parts[] = "({$this->roepnaam})";
        }
        if ($this->tussenvoegsel) {
            $parts[] = $this->tussenvoegsel;
        }
        if ($this->achternaam) {
            $parts[] = $this->achternaam;
        }

        return implode(' ', $parts);
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

    public function getRoepnaam()
    {
        return $this->roepnaam;
    }

    public function setRoepnaam($roepnaam)
    {
        $this->roepnaam = $roepnaam;

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

    public function getGeslacht()
    {
        return $this->geslacht;
    }

    public function setGeslacht(Geslacht $geslacht)
    {
        $this->geslacht = $geslacht;

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

    public function getAdres()
    {
        return $this->adres;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getPlaats()
    {
        return $this->plaats;
    }

    public function getMobiel()
    {
        return $this->mobiel;
    }

    public function getTelefoon()
    {
        return $this->telefoon;
    }

    public function getPostcodegebied()
    {
        return $this->postcodegebied;
    }

    public function isGeenPost()
    {
        return $this->geenPost;
    }

    public function isGeenEmail()
    {
        return $this->geenEmail;
    }

    public function getWerkgebied()
    {
        return $this->werkgebied;
    }

    public function setWerkgebied($werkgebied)
    {
        $this->werkgebied = $werkgebied;

        return $this;
    }
}
