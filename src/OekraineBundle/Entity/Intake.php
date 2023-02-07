<?php

namespace OekraineBundle\Entity;


use AppBundle\Entity\Legitimatie;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Verblijfsstatus;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Validator\NoFutureDate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="oekraine_intakes")
 * @Gedmo\Loggable
 * @UniqueEntity({"bezoeker", "intakedatum"}, message="Deze klant heeft al een intake op deze datum")
 */
class Intake
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Bezoeker
     *
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Bezoeker", inversedBy="intakes")
     * @Gedmo\Versioned
     */
    private $bezoeker;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @Gedmo\Versioned
     * @Assert\NotNull
     */
    private $medewerker;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Locatie")
     * @ORM\JoinColumn(name="intakelocatie_id")
     * @Gedmo\Versioned
     */
    private $intakelocatie;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Locatie")
     * @ORM\JoinColumn(name="woonlocatie_id")
     * @Gedmo\Versioned
     */
    private $woonlocatie;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $kamernummer;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_intake", type="date", nullable=true)
     * @Gedmo\Versioned
     * @Assert\NotNull
     * @Assert\Type("\DateTime")
     * @NoFutureDate
     */
    private $intakedatum;

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
     * @var Inkomen[]
     *
     * @ORM\ManyToMany(targetEntity="OekraineBundle\Entity\Inkomen")
     * @ORM\JoinTable(name="oekraine_inkomens_intakes")
     * @Assert\Count(min=1, minMessage="Selecteer tenminste één optie")
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
     * @ORM\JoinColumn(name="verblijfstatus_id", nullable=true)
     * @Assert\NotNull(groups={"toegang"})
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
     * @ORM\Column(name="legitimatie_geldig_tot", type="date", nullable=true)
     */
    private $legitimatieGeldigTot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="verblijf_in_NL_sinds", type="date", nullable=true)
     * @Assert\Type("\DateTime")
     * @NoFutureDate
     */
    private $verblijfInNederlandSinds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="verblijf_in_amsterdam_sinds", type="date", nullable=true)
     * @Assert\NotNull
     * @Assert\Type("\DateTime")
     * @NoFutureDate
     */
    private $verblijfInAmsterdamSinds;

    /**
     * @var string
     *
     * @ORM\Column(name="opmerking_andere_instanties", type="text", nullable=true)
     */
    private $opmerkingAndereInstanties;

    /**
     * @var string
     *
     * @ORM\Column(name="medische_achtergrond", type="text", nullable=true)
     */
    private $medischeAchtergrond;


    /**
     * @var string
     *
     * @ORM\Column(name="toekomstplannen", type="text", nullable=true)
     */
    private $toekomstplannen;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $indruk;

    /**
     * @var bool
     *
     * @ORM\Column(name="informele_zorg", nullable=true)
     */
    private $informeleZorg = false;

    /**
     * @var bool
     *
     * @ORM\Column(nullable=false)
     */
    private $werkhulp = false;

    /**
     * @var bool
     *
     * @ORM\Column(nullable=false)
     */
    private $hulpverlening = false;



    /**
     * @ORM\Column(name="geinformeerd_opslaan_gegevens", type="boolean")
     * @Gedmo\Versioned
     */
    protected $geinformeerdOpslaanGegevens = false;

    public function __construct(Bezoeker $bezoeker = null)
    {

        $this->setBezoeker($bezoeker);
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
        $this->intakedatum = new \DateTime();
        $this->inkomens = new ArrayCollection();
        $this->instanties = new ArrayCollection();

        if ($bezoeker) {
            $bezoeker->addIntake($this);
        }
    }

    public function __clone()
    {
        $this->id = null;
        $this->intakedatum = new \DateTime();
        $this->created = new \DateTime();
        $this->modified = new \DateTime();

        $this->informeleZorg = null;
        $this->werkhulp = null;
        $this->hulpverlening = null;

    }

    /**
     * @param \AppBundle\Entity\Medewerker $medewerker
     */
    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * @param \DateTime $intakedatum
     */
    public function setIntakedatum(\DateTime $intakedatum)
    {
        $this->intakedatum = $intakedatum;

        return $this;
    }


    /**
     * @param Inkomen[] $inkomens
     */
    public function setInkomens($inkomens)
    {
        $this->inkomens = $inkomens;

        return $this;
    }

    /**
     * @param string $inkomenOverig
     */
    public function setInkomenOverig($inkomenOverig)
    {
        $this->inkomenOverig = $inkomenOverig;

        return $this;
    }

    /**
     * @param Verblijfsstatus $verblijfsstatus
     */
    public function setVerblijfsstatus(Verblijfsstatus $verblijfsstatus)
    {
        $this->verblijfsstatus = $verblijfsstatus;

        return $this;
    }

    /**
     * @param \AppBundle\Entity\Legitimatie $legitimatie
     */
    public function setLegitimatie($legitimatie)
    {
        $this->legitimatie = $legitimatie;

        return $this;
    }

    /**
     * @param string $legitimatieNummer
     */
    public function setLegitimatieNummer($legitimatieNummer)
    {
        $this->legitimatieNummer = $legitimatieNummer;

        return $this;
    }

    /**
     * @param \DateTime $legitimatieGeldigTot
     */
    public function setLegitimatieGeldigTot(\DateTime $legitimatieGeldigTot = null)
    {
        $this->legitimatieGeldigTot = $legitimatieGeldigTot;

        return $this;
    }

    /**
     * @param \OekraineBundle\Entity\Woonsituatie $woonsituatie
     */
    public function setWoonsituatie($woonsituatie)
    {
        $this->woonsituatie = $woonsituatie;

        return $this;
    }

    /**
     * @param \DateTime $verblijfInNederlandSinds
     */
    public function setVerblijfInNederlandSinds(\DateTime $verblijfInNederlandSinds = null)
    {
        $this->verblijfInNederlandSinds = $verblijfInNederlandSinds;

        return $this;
    }

    /**
     * @param \DateTime $verblijfInAmsterdamSinds
     */
    public function setVerblijfInAmsterdamSinds(\DateTime $verblijfInAmsterdamSinds = null)
    {
        $this->verblijfInAmsterdamSinds = $verblijfInAmsterdamSinds;

        return $this;
    }

    /**
     * @param string $opmerkingAndereInstanties
     */
    public function setOpmerkingAndereInstanties($opmerkingAndereInstanties)
    {
        $this->opmerkingAndereInstanties = $opmerkingAndereInstanties;

        return $this;
    }

    /**
     * @param string $medischeAchtergrond
     */
    public function setMedischeAchtergrond($medischeAchtergrond)
    {
        $this->medischeAchtergrond = $medischeAchtergrond;

        return $this;
    }



    /**
     * @param Instantie[] $instanties
     */
    public function setInstanties($instanties)
    {
        $this->instanties = $instanties;

        return $this;
    }

    /**
     * @param string $telefoonnummer
     */
    public function setTelefoonnummer($telefoonnummer)
    {
        $this->telefoonnummer = $telefoonnummer;

        return $this;
    }

    /**
     * @param string $postadres
     */
    public function setPostadres($postadres)
    {
        $this->postadres = $postadres;

        return $this;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @param string $woonplaats
     */
    public function setWoonplaats($woonplaats)
    {
        $this->woonplaats = $woonplaats;

        return $this;
    }


    /**
     * @return string
     */
    public function getIndruk()
    {
        return $this->indruk;
    }

    /**
     * @param string $indruk
     */
    public function setIndruk($indruk)
    {
        $this->indruk = $indruk;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInformeleZorg()
    {
        return $this->informeleZorg;
    }

    /**
     * @param bool $informeleZorg
     */
    public function setInformeleZorg($informeleZorg)
    {
        $this->informeleZorg = $informeleZorg;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWerkhulp(): ?bool
    {
        return $this->werkhulp;
    }

    /**
     * @param bool $werkhulp
     * @return Intake
     */
    public function setWerkhulp(bool $werkhulp): Intake
    {
        $this->werkhulp = $werkhulp;
        return $this;
    }


    /**
     * @return bool
     */
    public function isHulpverlening()
    {
        return $this->hulpverlening;
    }

    /**
     * @param bool $hulpverlening
     */
    public function setHulpverlening($hulpverlening)
    {
        $this->hulpverlening = $hulpverlening;

        return $this;
    }

    /**
     * @return string
     */
    public function getToekomstplannen()
    {
        return $this->toekomstplannen;
    }

    /**
     * @param string $toekomstplannen
     */
    public function setToekomstplannen($toekomstplannen)
    {
        $this->toekomstplannen = $toekomstplannen;

        return $this;
    }



    public function getVerslavingen()
    {
        return $this->verslavingen;
    }

    public function getInstanties()
    {
        return $this->instanties;
    }

    public function getOpmerkingAndereInstanties()
    {
        return $this->opmerkingAndereInstanties;
    }

    public function getMedischeAchtergrond()
    {
        return $this->medischeAchtergrond;
    }

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

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Bezoeker
     */
    public function getBezoeker(): ?Bezoeker
    {
        return $this->bezoeker;
    }

    /**
     * @param Bezoeker $bezoeker
     * @return Intake
     */
    public function setBezoeker(Bezoeker $bezoeker): Intake
    {
        $this->bezoeker = $bezoeker;
        return $this;
    }


    public function getIntakedatum()
    {
        return $this->intakedatum;
    }

    public function getWoonlocatie()
    {
        return $this->woonlocatie;
    }

    public function setWoonlocatie(Locatie $locatie = null)
    {
        $this->woonlocatie = $locatie;

        return $this;
    }

    /**
     * @return Locatie
     */
    public function getIntakelocatie(): ?Locatie
    {
        return $this->intakelocatie;
    }

    /**
     * @param Locatie $intakelocatie
     * @return Intake
     */
    public function setIntakelocatie(Locatie $intakelocatie): Intake
    {
        $this->intakelocatie = $intakelocatie;
        return $this;
    }

    /**
     * @return string
     */
    public function getKamernummer(): ?string
    {
        return $this->kamernummer;
    }

    /**
     * @param string $kamernummer
     * @return Intake
     */
    public function setKamernummer(string $kamernummer): Intake
    {
        $this->kamernummer = $kamernummer;
        return $this;
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
    public function isGeinformeerdOpslaanGegevens(): bool
    {
        return $this->geinformeerdOpslaanGegevens;
    }

    /**
     * @param bool $geinformeerdOpslaanGegevens
     */
    public function setGeinformeerdOpslaanGegevens(bool $geinformeerdOpslaanGegevens): void
    {
        $this->geinformeerdOpslaanGegevens = $geinformeerdOpslaanGegevens;
    }

    /**
     * Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        return;
        $root = $context->getRoot();
        if ($root instanceof Form && $root->getName()) {
            if ($root->getName() == "toegang") {

            }
        }
        return $context->getValidator()->validate();
    }
}
