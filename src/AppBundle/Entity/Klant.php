<?php

namespace AppBundle\Entity;

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
    use PersoonTrait;

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
     * @Column(type="string", nullable=true)
     */
    private $roepnaam;

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
     * @Column(name="last_zrm", type="date")
     */
    private $laatsteZrm;

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
     * @Column(type="string", nullable=true)
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
     * @Column(type="text", nullable=true)
     */
    private $opmerking;

    /**
     * @Column(type="boolean")
     */
    private $overleden;

    /**
     * @Column(type="boolean")
     */
    private $disabled = false;

    public function getId()
    {
        return $this->id;
    }

//     public function setId($id)
//     {
//         $this->id = $id;

//         return $this;
//     }

    public function getRoepnaam()
    {
        return $this->roepnaam;
    }

    public function setRoepnaam($roepnaam)
    {
        $this->roepnaam = $roepnaam;

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

    public function getGeslacht()
    {
        return $this->geslacht;
    }

    public function setGeslacht(Geslacht $geslacht)
    {
        $this->geslacht = $geslacht;

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

    public function getNationaliteit()
    {
        return $this->nationaliteit;
    }

    public function setNationaliteit(Nationaliteit $nationaliteit)
    {
        $this->nationaliteit = $nationaliteit;

        return $this;
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

    public function getLaatsteZrm()
    {
        return $this->laatsteZrm;
    }

    public function setLaastseZrm(\DateTime $laatsteZrm)
    {
        $this->laatsteZrm = $laatsteZrm;

        return $this;
    }
}
