<?php

namespace TwBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\KlantRelationInterface;
use AppBundle\Model\OptionalMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\UsesKlantTrait;
use AppBundle\Service\KlantDaoInterface;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_deelnemers")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({"Klant" = "Klant", "Verhuurder" = "Verhuurder"})
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="aanmelddatum", type="date")
     * @Gedmo\Versioned
     */
    protected $aanmelddatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="afsluitdatum", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $afsluitdatum;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $rekeningnummer;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    protected $wpi = false;

    /**
     * @var Inkomen
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Inkomen",cascade={"persist"})
     */
    protected $inkomen;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $klantmanager;

    /**
     * @var AppKlant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $appKlant;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="tw_deelnemer_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    protected $verslagen;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="tw_deelnemer_document")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    protected $documenten;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $ambulantOndersteuner;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $begeleider;

    /**
     * @var Dagbesteding
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Dagbesteding",cascade={"persist"})
     */
    private $dagbesteding;

    /**
     * @var Ritme
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Ritme",cascade={"persist"})
     */
    private $ritme;

    /**
     * @var Huisdieren
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Huisdieren",cascade={"persist"})
     */
    private $huisdieren;

    /**
     * @var Roken
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Roken",cascade={"persist"})
     */
    private $roken;

    /**
     * @var Softdrugs
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Softdrugs",cascade={"persist"})
     */
    private $softdrugs;

    /**
     * @var Alcohol
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Alcohol",cascade={"persist"})
     */
    private $alcohol;

    /**
     * @var Traplopen
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Traplopen",cascade={"persist"})
     */
    private $traplopen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

    /**
     * @return Dagbesteding
     */
    public function getDagbesteding(): ?Dagbesteding
    {
        return $this->dagbesteding;
    }

    /**
     * @param Dagbesteding $dagbesteding
     * @return Klant
     */
    public function setDagbesteding(Dagbesteding $dagbesteding): Klant
    {
        $this->dagbesteding = $dagbesteding;
        return $this;
    }

    /**
     * @return Ritme
     */
    public function getRitme(): ?Ritme
    {
        return $this->ritme;
    }

    /**
     * @param Ritme $ritme
     * @return Klant
     */
    public function setRitme(Ritme $ritme): ?Klant
    {
        $this->ritme = $ritme;
        return $this;
    }

    /**
     * @return Huisdieren
     */
    public function getHuisdieren(): ?Huisdieren
    {
        return $this->huisdieren;
    }

    /**
     * @param Huisdieren $huisdieren
     * @return Klant
     */
    public function setHuisdieren(Huisdieren $huisdieren): Klant
    {
        $this->huisdieren = $huisdieren;
        return $this;
    }

    /**
     * @return Roken
     */
    public function getRoken(): ?Roken
    {
        return $this->roken;
    }

    /**
     * @param Roken $roken
     * @return Klant
     */
    public function setRoken(Roken $roken): Klant
    {
        $this->roken = $roken;
        return $this;
    }

    /**
     * @return Softdrugs
     */
    public function getSoftdrugs(): ?Softdrugs
    {
        return $this->softdrugs;
    }

    /**
     * @param Softdrugs $softdrugs
     * @return Klant
     */
    public function setSoftdrugs(Softdrugs $softdrugs): Klant
    {
        $this->softdrugs = $softdrugs;
        return $this;
    }

    /**
     * @return Alcohol
     */
    public function getAlcohol(): ?Alcohol
    {
        return $this->alcohol;
    }

    /**
     * @param Alcohol $alcohol
     * @return Deelnemer
     */
    public function setAlcohol(Alcohol $alcohol): Deelnemer
    {
        $this->alcohol = $alcohol;
        return $this;
    }


    /**
     * @return Traplopen
     */
    public function getTraplopen(): ?Traplopen
    {
        return $this->traplopen;
    }

    /**
     * @param Traplopen $traplopen
     * @return Klant
     */
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

    public function getKlant()
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

    public function getIntake()
    {
        return $this->intake;
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

    public function setAfsluitdatum(\DateTime $afsluitdatum)
    {
        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }

    public function reopen()
    {
        $this->afsluitdatum = null;
        $this->afsluiting = null;

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

    public function getKlantFieldName()
    {
        return "klant";
    }


    /**
     * @return Medewerker
     */
    public function getAmbulantOndersteuner(): ?Medewerker
    {
        return $this->ambulantOndersteuner;
    }

    /**
     * @param Medewerker $ambulantOndersteuner
     */
    public function setAmbulantOndersteuner(?Medewerker $ambulantOndersteuner): void
    {
        $this->ambulantOndersteuner = $ambulantOndersteuner;
    }

    /**
     * @return Inkomen
     */
    public function getInkomen(): ?Inkomen
    {
        return $this->inkomen;
    }

    /**
     * @param Inkomen $inkomen
     * @return Deelnemer
     */
    public function setInkomen(Inkomen $inkomen): Deelnemer
    {
        $this->inkomen = $inkomen;
        return $this;
    }

    /**
     * @return string
     */
    public function getBegeleider(): ?string
    {
        return $this->begeleider;
    }

    /**
     * @param string $begeleider
     * @return Klant
     */
    public function setBegeleider(string $begeleider): Klant
    {
        $this->begeleider = $begeleider;
        return $this;
    }


    abstract public function getHuurovereenkomsten();

    public function isGekoppeld()
    {
        foreach ($this->getHuurovereenkomsten() as $hoe) {
            /** @var Huurovereenkomst $hoe */
            $hoe = $hoe;
            if ($hoe->isReservering() == false && $hoe->isActief() == true && $hoe->getAfsluitdatum() == null && $hoe->getStartdatum() != null
                && $this->getAfsluitdatum() == null
            ) {
                return true;
            }
        }
        return false;
    }
}
