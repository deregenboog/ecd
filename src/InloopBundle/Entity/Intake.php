<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Inkomen;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Legitimatie;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Verblijfsstatus;
use AppBundle\Entity\Zrm;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Validator\NoFutureDate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="intakes")
 *
 * @Gedmo\Loggable
 *
 * @UniqueEntity({"klant", "intakedatum"}, message="Deze klant heeft al een intake op deze datum")
 */
class Intake
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="intakes")
     *
     * @Gedmo\Versioned
     *
     * @Assert\NotNull
     */
    private $klant;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker", cascade={"persist"})
     *
     * @Gedmo\Versioned
     *
     * @Assert\NotNull
     */
    private $medewerker;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     *
     * @ORM\JoinColumn(name="locatie2_id")
     *
     * @Gedmo\Versioned
     */
    private $intakelocatie;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     *
     * @ORM\JoinColumn(name="locatie1_id")
     *
     * @Gedmo\Versioned
     */
    private $gebruikersruimte;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     *
     * @ORM\JoinColumn(name="locatie3_id")
     *
     * @Gedmo\Versioned
     */
    private $locatie3;

    /**
     * @var Locatie
     *
     * @ORM\ManyToMany(targetEntity="Locatie", inversedBy="accessIntakes")
     *
     * @ORM\JoinTable(name="locaties_accessintakes")
     *
     * @var Collection<int, Locatie>
     */
    private Collection $specifiekeLocaties;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum_intake", type="date", nullable=true)
     *
     * @Gedmo\Versioned
     *
     * @Assert\NotNull
     *
     * @Assert\Type("\DateTime")
     *
     * @NoFutureDate
     */
    private $intakedatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="overigen_toegang_van", type="date", nullable=true)
     *
     * @Gedmo\Versioned
     *
     * @Assert\Type("\DateTime", groups={"toegang"})
     */
    private $overigenToegangVan;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $postadres;

    /**
     * @var string
     *
     * @ORM\Column(length=7, nullable=true)
     *
     * @Gedmo\Versioned
     *
     * @Assert\Length(min=6,max=7)
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $woonplaats;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $telefoonnummer;

    /**
     * @var bool
     *
     * @ORM\Column(name="toegang_inloophuis", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $toegangInloophuis;


    /**
     * @var bool
     *
     * @ORM\Column(name="beschikking_wachtlijstbegeleiding", type="boolean", nullable=false,options={"default"=false})
     *
     * @Gedmo\Versioned
     */
    private $beschikkingWachtlijstbegeleiding = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="mag_gebruiken", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $magGebruiken;

    /**
     * @var Inkomen[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Inkomen")
     *
     * @ORM\JoinTable(name="inkomens_intakes")
     *
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
     *
     * @ORM\JoinColumn(name="verblijfstatus_id")
     *
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
     * @var Woonsituatie
     *
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Woonsituatie")
     *
     * @Assert\NotNull
     */
    private $woonsituatie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="verblijf_in_NL_sinds", type="date", nullable=true)
     *
     * @Assert\Type("\DateTime")
     *
     * @NoFutureDate
     */
    private $verblijfInNederlandSinds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="verblijf_in_amsterdam_sinds", type="date", nullable=true)
     *
     * @Assert\NotNull
     *
     * @Assert\Type("\DateTime")
     *
     * @NoFutureDate
     */
    private $verblijfInAmsterdamSinds;

    /**
     * @var string
     *
     * @ORM\Column(name="opmerking_andere_instanties", type="text", length=65535, nullable=true)
     */
    private $opmerkingAndereInstanties;

    /**
     * @var string
     *
     * @ORM\Column(name="medische_achtergrond", type="text", length=65535, nullable=true)
     */
    private $medischeAchtergrond;

    /**
     * @var string
     *
     * @ORM\Column(name="verwachting_dienstaanbod", type="text", length=65535, nullable=true)
     *
     * @Assert\NotBlank
     */
    private $verwachtingDienstaanbod;

    /**
     * @var string
     *
     * @ORM\Column(name="toekomstplannen", type="text", length=65535, nullable=true)
     *
     * @Assert\NotBlank
     */
    private $toekomstplannen;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $indruk;

    /**
     * @var bool
     *
     * @ORM\Column(name="informele_zorg", type="boolean", nullable=true, options={"default":0})
     */
    private $informeleZorg = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $dagbesteding = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $inloophuis = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $hulpverlening = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Assert\NotNull
     */
    private $doelgroep;

    /**
     * @var Infobaliedoelgroep
     *
     * @ORM\ManyToOne(targetEntity="Infobaliedoelgroep")
     */
    private $infobaliedoelgroep;

    /**
     * @var Verslaving
     *
     * @ORM\ManyToOne(targetEntity="Verslaving")
     */
    private $primaireProblematiek;

    /**
     * @var Frequentie
     *
     * @ORM\ManyToOne(targetEntity="Frequentie")
     *
     * @ORM\JoinColumn(name="primaireproblematieksfrequentie_id")
     */
    private $primaireProblematiekFrequentie;

    /**
     * @var Periode
     *
     * @ORM\ManyToOne(targetEntity="Periode")
     *
     * @ORM\JoinColumn(name="primaireproblematieksperiode_id")
     */
    private $primaireProblematiekPeriode;

    /**
     * @var Gebruikswijze
     *
     * @ORM\ManyToMany(targetEntity="Gebruikswijze")
     *
     * @ORM\JoinTable(
     *     name="intakes_primaireproblematieksgebruikswijzen",
     *     inverseJoinColumns=@ORM\JoinColumn(name="primaireproblematieksgebruikswijze_id")
     * )
     */
    private $primaireProblematiekGebruikswijzen;

    /**
     * @var Verslaving[]
     *
     * @ORM\ManyToMany(targetEntity="Verslaving")
     *
     * @ORM\JoinTable(name="intakes_verslavingen")
     */
    private $verslavingen;

    /**
     * @var Frequentie
     *
     * @ORM\ManyToOne(targetEntity="Frequentie")
     *
     * @ORM\JoinColumn(name="verslavingsfrequentie_id")
     */
    private $frequentie;

    /**
     * @var Periode
     *
     * @ORM\ManyToOne(targetEntity="Periode")
     *
     * @ORM\JoinColumn(name="verslavingsperiode_id")
     */
    private $periode;

    /**
     * @var Gebruikswijze[]
     *
     * @ORM\ManyToMany(targetEntity="Gebruikswijze")
     *
     * @ORM\JoinTable(
     *     name="intakes_verslavingsgebruikswijzen",
     *     inverseJoinColumns=@ORM\JoinColumn(name="verslavingsgebruikswijze_id")
     * )
     */
    private $gebruikswijzen;

    /**
     * @var string
     *
     * @ORM\Column(name="verslaving_overig", nullable=true)
     */
    private $verslavingOverig;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eerste_gebruik", type="date", nullable=true)
     */
    private $eersteGebruik;

    /**
     * @var Instantie[]
     *
     * @ORM\ManyToMany(targetEntity="Instantie")
     *
     * @ORM\JoinTable(name="instanties_intakes")
     */
    private $instanties;

    /**
     * @var Zrm
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Zrm", cascade={"persist"})
     */
    private $zrm;

    /**
     * @ORM\Column(name="geinformeerd_opslaan_gegevens", type="boolean")
     *
     * @Gedmo\Versioned
     */
    protected $geinformeerdOpslaanGegevens = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct(?Klant $klant = null)
    {
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
        $this->intakedatum = new \DateTime();
        $this->inkomens = new ArrayCollection();
        $this->primaireProblematiekGebruikswijzen = new ArrayCollection();
        $this->verslavingen = new ArrayCollection();
        $this->gebruikswijzen = new ArrayCollection();
        $this->instanties = new ArrayCollection();

        if ($klant) {
            $klant->addIntake($this);
        }
    }

    public function __clone()
    {
        $this->id = null;
        $this->intakedatum = new \DateTime();
        $this->created = new \DateTime();
        $this->modified = new \DateTime();

        $this->primaireProblematiek = null;
        $this->primaireProblematiekFrequentie = null;
        $this->primaireProblematiekGebruikswijzen = null;
        $this->primaireProblematiekPeriode = null;

        $this->informeleZorg = null;
        $this->dagbesteding = null;
        $this->inloophuis = null;
        $this->hulpverlening = null;

        if ($this->zrm && get_class($this->zrm) === get_class(Zrm::create())) {
            $this->zrm = clone $this->zrm;
        } else {
            $this->zrm = null;
        }
    }

    /**
     * @param Medewerker $medewerker
     */
    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function setIntakedatum(\DateTime $intakedatum)
    {
        $this->intakedatum = $intakedatum;

        return $this;
    }

    public function setOverigenToegangVan(?\DateTime $overigenToegangVan = null)
    {
        $this->overigenToegangVan = $overigenToegangVan;

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

    public function setVerblijfsstatus(Verblijfsstatus $verblijfsstatus)
    {
        $this->verblijfsstatus = $verblijfsstatus;

        return $this;
    }

    /**
     * @param Legitimatie $legitimatie
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

    public function setLegitimatieGeldigTot(?\DateTime $legitimatieGeldigTot = null)
    {
        $this->legitimatieGeldigTot = $legitimatieGeldigTot;

        return $this;
    }

    /**
     * @param Woonsituatie $woonsituatie
     */
    public function setWoonsituatie($woonsituatie)
    {
        $this->woonsituatie = $woonsituatie;

        return $this;
    }

    public function setVerblijfInNederlandSinds(?\DateTime $verblijfInNederlandSinds = null)
    {
        $this->verblijfInNederlandSinds = $verblijfInNederlandSinds;

        return $this;
    }

    public function setVerblijfInAmsterdamSinds(?\DateTime $verblijfInAmsterdamSinds = null)
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
     * @param Verslaving $primaireProblematiek
     */
    public function setPrimaireProblematiek($primaireProblematiek)
    {
        $this->primaireProblematiek = $primaireProblematiek;

        return $this;
    }

    /**
     * @param Frequentie $primaireProblematiekFrequentie
     */
    public function setPrimaireProblematiekFrequentie($primaireProblematiekFrequentie)
    {
        $this->primaireProblematiekFrequentie = $primaireProblematiekFrequentie;

        return $this;
    }

    /**
     * @param Periode $primaireProblematiekPeriode
     */
    public function setPrimaireProblematiekPeriode($primaireProblematiekPeriode)
    {
        $this->primaireProblematiekPeriode = $primaireProblematiekPeriode;

        return $this;
    }

    /**
     * @param Gebruikswijze $primaireProblematiekGebruikswijzen
     */
    public function setPrimaireProblematiekGebruikswijzen($primaireProblematiekGebruikswijzen)
    {
        $this->primaireProblematiekGebruikswijzen = $primaireProblematiekGebruikswijzen;

        return $this;
    }

    /**
     * @param Verslaving[] $verslavingen
     */
    public function setVerslavingen($verslavingen)
    {
        $this->verslavingen = $verslavingen;

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
     * @return Infobaliedoelgroep
     */
    public function getInfobaliedoelgroep()
    {
        return $this->infobaliedoelgroep;
    }

    public function setInfobaliedoelgroep(?Infobaliedoelgroep $infobaliedoelgroep = null)
    {
        $this->infobaliedoelgroep = $infobaliedoelgroep;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEersteGebruik()
    {
        return $this->eersteGebruik;
    }

    public function setEersteGebruik(?\DateTime $eersteGebruik = null)
    {
        $this->eersteGebruik = $eersteGebruik;

        return $this;
    }

    /**
     * @return Frequentie
     */
    public function getFrequentie()
    {
        return $this->frequentie;
    }

    /**
     * @param Frequentie $frequentie
     */
    public function setFrequentie($frequentie)
    {
        $this->frequentie = $frequentie;

        return $this;
    }

    /**
     * @return Periode
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * @param Periode $periode
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * @return Gebruikswijze[]
     */
    public function getGebruikswijzen()
    {
        return $this->gebruikswijzen;
    }

    /**
     * @param Gebruikswijze[] $gebruikswijzen
     */
    public function setGebruikswijzen($gebruikswijzen)
    {
        $this->gebruikswijzen = $gebruikswijzen;

        return $this;
    }

    /**
     * @return string
     */
    public function getVerslavingOverig()
    {
        return $this->verslavingOverig;
    }

    /**
     * @param string $verslavingOverig
     */
    public function setVerslavingOverig($verslavingOverig = null)
    {
        $this->verslavingOverig = $verslavingOverig;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getPrimaireProblematiek()
    {
        return $this->primaireProblematiek;
    }

    public function getPrimaireProblematiekFrequentie()
    {
        return $this->primaireProblematiekFrequentie;
    }

    public function getPrimaireProblematiekPeriode()
    {
        return $this->primaireProblematiekPeriode;
    }

    public function getPrimaireProblematiekGebruikswijzen()
    {
        return $this->primaireProblematiekGebruikswijzen;
    }

    /**
     * @return bool
     */
    public function isDoelgroep()
    {
        return $this->doelgroep;
    }

    /**
     * @param bool $doelgroep
     */
    public function setDoelgroep($doelgroep)
    {
        $this->doelgroep = $doelgroep;

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
    public function isDagbesteding()
    {
        return $this->dagbesteding;
    }

    /**
     * @param bool $dagbesteding
     */
    public function setDagbesteding($dagbesteding)
    {
        $this->dagbesteding = $dagbesteding;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInloophuis()
    {
        return $this->inloophuis;
    }

    /**
     * @param bool $inloophuis
     */
    public function setInloophuis($inloophuis)
    {
        $this->inloophuis = $inloophuis;

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

    /**
     * @return string
     */
    public function getVerwachtingDienstaanbod()
    {
        return $this->verwachtingDienstaanbod;
    }

    /**
     * @param string $verwachtingDienstaanbod
     */
    public function setVerwachtingDienstaanbod($verwachtingDienstaanbod)
    {
        $this->verwachtingDienstaanbod = $verwachtingDienstaanbod;

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

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
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

    public function setIntakelocatie(?Locatie $locatie = null)
    {
        $this->intakelocatie = $locatie;

        return $this;
    }

    public function getGebruikersruimte()
    {
        return $this->gebruikersruimte;
    }

    public function setGebruikersruimte(?Locatie $locatie = null)
    {
        $this->gebruikersruimte = $locatie;

        return $this;
    }

    public function getLocatie3()
    {
        return $this->locatie3;
    }

    public function setLocatie3(?Locatie $locatie = null)
    {
        $this->locatie3 = $locatie;

        return $this;
    }

    public function isBeschikkingWachtlijstbegeleiding(): bool
    {
        return $this->beschikkingWachtlijstbegeleiding??false;
    }

    public function setBeschikkingWachtlijstbegeleiding(bool $beschikkingWachtlijstbegeleiding): void
    {
        $this->beschikkingWachtlijstbegeleiding = $beschikkingWachtlijstbegeleiding;
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

    public function getOverigenToegangVan()
    {
        return $this->overigenToegangVan;
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

    public function getZrm()
    {
        return $this->zrm;
    }

    public function setZrm(Zrm $zrm)
    {
        $zrm
            ->setRequestModule('Intake')
            ->setKlant($this->klant)
        ;
        $this->zrm = $zrm;

        return $this;
    }

    public function isGeinformeerdOpslaanGegevens(): bool
    {
        return $this->geinformeerdOpslaanGegevens;
    }

    public function setGeinformeerdOpslaanGegevens(bool $geinformeerdOpslaanGegevens): void
    {
        $this->geinformeerdOpslaanGegevens = $geinformeerdOpslaanGegevens;
    }

    public function getSpecifiekeLocaties(): Collection
    {
        return $this->specifiekeLocaties;
    }

    public function setSpecifiekeLocaties(Collection $specifiekeLocaties): void
    {
        $this->specifiekeLocaties = $specifiekeLocaties;
    }

    /**
     * Assert\Callback.
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        return;
        $root = $context->getRoot();
        if ($root instanceof Form && $root->getName()) {
            if ('toegang' == $root->getName()) {
            }
        }

        return $context->getValidator()->validate();
    }

    /**
     * @deprecated
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $klant_id_before_constraint;

    /**
     * @deprecated
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $medewerker_id_before_constraint;

    /**
     * @deprecated
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $toegang_vrouwen_nacht_opvang;

    /**
     * @deprecated
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $woonsituatie_id_before_constraint;
}
