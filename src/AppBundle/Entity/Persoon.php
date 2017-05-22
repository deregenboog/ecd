<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Persoon
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    protected $voornaam;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $roepnaam;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $tussenvoegsel;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Gedmo\Versioned
     */
    protected $achternaam;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $geboortedatum;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\Column(name="BSN", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $bsn;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @var Geslacht
     * @ORM\ManyToOne(targetEntity="Geslacht")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $geslacht;

    /**
     * @var Land
     * @ORM\ManyToOne(targetEntity="Land")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $land;

    /**
     * @var Nationaliteit
     * @ORM\ManyToOne(targetEntity="Nationaliteit")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $nationaliteit;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $adres;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $plaats;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $werkgebied;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $postcodegebied;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $mobiel;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $telefoon;

    /**
     * @ORM\Column(name="geen_post", type="boolean")
     * @Gedmo\Versioned
     */
    protected $geenPost;

    /**
     * @ORM\Column(name="geen_email", type="boolean")
     * @Gedmo\Versioned
     */
    protected $geenEmail;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $opmerking;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    protected $disabled = false;

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

    public function setPostcodegebied($postcodegebied)
    {
        $this->postcodegebied = $postcodegebied;

        return $this;
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

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getLand()
    {
        return $this->land;
    }

    public function setLand(Land $land)
    {
        $this->land = $land;

        return $this;
    }

    public function getBsn()
    {
        return $this->bsn;
    }

    public function setBsn($bsn)
    {
        $this->bsn = $bsn;

        return $this;
    }

    public function getNationaliteit()
    {
        return $this->nationaliteit;
    }

    public function setNationaliteit(Nationaliteit $nationaliteit)
    {
        $this->nationaliteit = $nationaliteit;

        return $this;
    }

    public function setAdres($adres)
    {
        $this->adres = $adres;

        return $this;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function setPlaats($plaats)
    {
        $this->plaats = $plaats;

        return $this;
    }

    public function setMobiel($mobiel)
    {
        $this->mobiel = $mobiel;

        return $this;
    }

    public function setTelefoon($telefoon)
    {
        $this->telefoon = $telefoon;

        return $this;
    }

    public function getGeenPost()
    {
        return $this->geenPost;
    }

    public function setGeenPost($geenPost)
    {
        $this->geenPost = $geenPost;

        return $this;
    }

    public function getGeenEmail()
    {
        return $this->geenEmail;
    }

    public function setGeenEmail($geenEmail)
    {
        $this->geenEmail = $geenEmail;

        return $this;
    }

    public function getOpmerking()
    {
        return $this->opmerking;
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;

        return $this;
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }
}
