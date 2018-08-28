<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Inkomen;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Legitimatie;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Verblijfsstatus;
use AppBundle\Entity\Woonsituatie;
use AppBundle\Model\ZrmInterface;
use AppBundle\Model\ZrmTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="intakes")
 * @Gedmo\Loggable
 */
class Intake implements ZrmInterface
{
    use ZrmTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="intakes")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     * @ORM\JoinColumn(name="locatie2_id")
     * @Gedmo\Versioned
     */
    private $intakelocatie;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     * @ORM\JoinColumn(name="locatie1_id")
     * @Gedmo\Versioned
     */
    private $gebruikersruimte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_intake", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $intakedatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="amoc_toegang_tot", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $amocToegangTot;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $postadres;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $woonplaats;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $telefoonnummer;

    /**
     * @var bool
     *
     * @ORM\Column(name="toegang_inloophuis", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $toegangInloophuis;

    /**
     * @var bool
     *
     * @ORM\Column(name="mag_gebruiken", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $magGebruiken;

    /**
     * @var Inkomen[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Inkomen")
     * @ORM\JoinTable(name="inkomens_intakes")
     */
    private $inkomens;

    /**
     * @var string
     *
     * @ORM\Column(name="inkomen_overig", nullable=true)
     */
    private $inkomenOverig;

    /**
     * @var Verblijfsstatus
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Verblijfsstatus")
     * @ORM\JoinColumn(name="verblijfstatus_id")
     */
    private $verblijfsstatus;

    /**
     * @var Legitimatie
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Legitimatie")
     */
    private $legitimatie;

    /**
     * @var string
     *
     * @ORM\Column(name="legitimatie_nummer", nullable=true)
     */
    private $legitimatieNummer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="legitimatie_geldig_tot", nullable=true)
     */
    private $legitimatieGeldigTot;

    /**
     * @var Woonsituatie
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Woonsituatie")
     */
    private $woonsituatie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="verblijf_in_NL_sinds", nullable=true)
     */
    private $verblijfInNederlandSinds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="verblijf_in_amsterdam_sinds", nullable=true)
     */
    private $verblijfInAmsterdamSinds;

    /**
     * @return \DateTime
     */
    public function getVerblijfInNederlandSinds()
    {
        return $this->verblijfInNederlandSinds;
    }

    /**
     * @return \DateTime
     */
    public function getVerblijfInAmsterdamSinds()
    {
        return $this->verblijfInAmsterdamSinds;
    }

    /**
     * @return string
     */
    public function getTelefoonnummer()
    {
        return $this->telefoonnummer;
    }

    /**
     * @return string
     */
    public function getPostadres()
    {
        return $this->postadres;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @return string
     */
    public function getWoonplaats()
    {
        return $this->woonplaats;
    }

    public function __construct()
    {
        $this->inkomens = new ArrayCollection();
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
    }

    public function isToegangInloophuis()
    {
        return $this->toegangInloophuis;
    }

    public function setToegangInloophuis($toegangInloophuis)
    {
        $this->toegangInloophuis = $toegangInloophuis;

        return $this;
    }

    public function getIntakedatum()
    {
        return $this->intakedatum;
    }

    public function getIntakelocatie()
    {
        return $this->intakelocatie;
    }

    public function setIntakelocatie(Locatie $locatie)
    {
        $this->intakelocatie = $locatie;
    }

    public function getGebruikersruimte()
    {
        return $this->gebruikersruimte;
    }

    public function setGebruikersruimte(Locatie $locatie)
    {
        $this->gebruikersruimte = $locatie;
    }

    public function getInkomens()
    {
        return $this->inkomens;
    }

    public function getVerblijfsstatus()
    {
        return $this->verblijfsstatus;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function getAmocToegangTot()
    {
        return $this->amocToegangTot;
    }

    public function getLegitimatie()
    {
        return $this->legitimatie;
    }

    public function getLegitimatieNummer()
    {
        return $this->legitimatieNummer;
    }

    public function getLegitimatieGeldigTot()
    {
        return $this->legitimatieGeldigTot;
    }

    public function getInkomenOverig()
    {
        return $this->inkomenOverig;
    }

    public function getWoonsituatie()
    {
        return $this->woonsituatie;
    }

    /**
     * @return bool
     */
    public function isMagGebruiken()
    {
        return $this->magGebruiken;
    }

    /**
     * @param bool $magGebruiken
     */
    public function setMagGebruiken($magGebruiken)
    {
        $this->magGebruiken = $magGebruiken;

        return $this;
    }
}
