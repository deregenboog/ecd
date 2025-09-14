<?php

namespace TwBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\KlantRelationInterface;
use AppBundle\Model\OptionalMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\UsesKlantTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="tw_deelnemers")
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 *
 * @ORM\DiscriminatorColumn(name="model", type="string")
 *
 * @ORM\DiscriminatorMap({"Klant" = "Klant", "Verhuurder" = "Verhuurder"})
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
abstract class Deelnemer implements KlantRelationInterface
{
    use TimestampableTrait;
    use OptionalMedewerkerTrait;
    use UsesKlantTrait;

    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="aanmelddatum", type="date")
     *
     * @Gedmo\Versioned
     */
    protected $aanmelddatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="afsluitdatum", type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $afsluitdatum;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $rekeningnummer;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    protected $wpi = false;

    /**
     * @var Inkomen
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Inkomen",cascade={"persist"})
     */
    protected $inkomen;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $klantmanager;

    /**
     * @var AppKlant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    protected $appKlant;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     *
     * @ORM\JoinTable(name="tw_deelnemer_verslag")
     *
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    protected $verslagen;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     *
     * @ORM\JoinTable(name="tw_deelnemer_document")
     *
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    protected $documenten;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $ambulantOndersteuner;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $begeleider;

    /**
     * @var Dagbesteding
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Dagbesteding",cascade={"persist"})
     */
    private $dagbesteding;

    /**
     * @var Ritme
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Ritme",cascade={"persist"})
     */
    private $ritme;

    /**
     * @var Huisdieren
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Huisdieren",cascade={"persist"})
     */
    private $huisdieren;

    /**
     * @var Roken
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Roken",cascade={"persist"})
     */
    private $roken;

    /**
     * @var Softdrugs
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Softdrugs",cascade={"persist"})
     */
    private $softdrugs;

    /**
     * @var Alcohol
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Alcohol",cascade={"persist"})
     */
    private $alcohol;

    /**
     * @var Traplopen
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Traplopen",cascade={"persist"})
     */
    private $traplopen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function getDagbesteding(): ?Dagbesteding
    {
        return $this->dagbesteding;
    }

    public function setDagbesteding(Dagbesteding $dagbesteding): Klant
    {
        $this->dagbesteding = $dagbesteding;

        return $this;
    }

    public function getRitme(): ?Ritme
    {
        return $this->ritme;
    }

    public function setRitme(Ritme $ritme): ?Klant
    {
        $this->ritme = $ritme;

        return $this;
    }

    public function getHuisdieren(): ?Huisdieren
    {
        return $this->huisdieren;
    }

    public function setHuisdieren(Huisdieren $huisdieren): Klant
    {
        $this->huisdieren = $huisdieren;

        return $this;
    }

    public function getRoken(): ?Roken
    {
        return $this->roken;
    }

    public function setRoken(Roken $roken): Klant
    {
        $this->roken = $roken;

        return $this;
    }

    public function getSoftdrugs(): ?Softdrugs
    {
        return $this->softdrugs;
    }

    public function setSoftdrugs(Softdrugs $softdrugs): Klant
    {
        $this->softdrugs = $softdrugs;

        return $this;
    }

    public function getAlcohol(): ?Alcohol
    {
        return $this->alcohol;
    }

    public function setAlcohol(Alcohol $alcohol): Deelnemer
    {
        $this->alcohol = $alcohol;

        return $this;
    }

    public function getTraplopen(): ?Traplopen
    {
        return $this->traplopen;
    }

    public function setTraplopen(Traplopen $traplopen): Klant
    {
        $this->traplopen = $traplopen;

        return $this;
    }

    public function __construct()
    {
        $this->aanmelddatum = new \DateTime();

        $this->verslagen = new ArrayCollection();
        $this->documenten = new ArrayCollection();
    }
    /*
     * Dit gaf een exceptie bij koppelingen maken in de view van een huurverzoek. Onderstaande __toString code uit de OekBundle gehaald. Lijkt me prima.
     * @190627 Laat dit even staan voor de zekerheid. jtborger
     */
    //    public function __toString()
    //    {
    //        return (string) $this->klant;
    //    }

    public function __toString()
    {
        try {
            return NameFormatter::formatFormal($this->appKlant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKlant(): AppKlant
    {
        return $this->appKlant;
    }

    public function getAppKlant()
    {
        return $this->appKlant;
    }

    public function setAppKlant(AppKlant $klant)
    {
        $this->appKlant = $klant;

        return $this;
    }

    public function getAanmelddatum()
    {
        return $this->aanmelddatum;
    }

    public function setAanmelddatum($aanmelddatum)
    {
        $this->aanmelddatum = $aanmelddatum;

        return $this;
    }

    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum(?\DateTime $afsluitdatum)
    {
        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }

    public function reopen(Medewerker $medewerker = null)
    {
        $this->afsluitdatum = null;
        return $this;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;

        return $this;
    }

    public function getDocumenten()
    {
        return $this->documenten;
    }

    public function addDocument(Document $document)
    {
        $this->documenten[] = $document;

        return $this;
    }

    public function getRekeningnummer()
    {
        return $this->rekeningnummer;
    }

    public function setRekeningnummer($rekeningnummer)
    {
        $this->rekeningnummer = $rekeningnummer;

        return $this;
    }

    public function isWpi()
    {
        return (bool) $this->wpi;
    }

    public function setWpi($wpi)
    {
        $this->wpi = $wpi;

        return $this;
    }

    public function getKlantmanager()
    {
        return $this->klantmanager;
    }

    public function setKlantmanager($klantmanager)
    {
        $this->klantmanager = $klantmanager;

        return $this;
    }

    public function getKlantFieldName(): string
    {
        return 'klant';
    }

    public function getAmbulantOndersteuner(): ?Medewerker
    {
        return $this->ambulantOndersteuner;
    }

    public function setAmbulantOndersteuner(?Medewerker $ambulantOndersteuner): void
    {
        $this->ambulantOndersteuner = $ambulantOndersteuner;
    }

    public function getInkomen(): ?Inkomen
    {
        return $this->inkomen;
    }

    public function setInkomen(?Inkomen $inkomen): Deelnemer
    {
        $this->inkomen = $inkomen;

        return $this;
    }

    public function getBegeleider(): ?string
    {
        return $this->begeleider;
    }

    public function setBegeleider(string $begeleider): Klant
    {
        $this->begeleider = $begeleider;

        return $this;
    }

    abstract public function getHuurovereenkomsten();

    abstract public function isGekoppeld();
}
