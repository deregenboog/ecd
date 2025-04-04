<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="iz_koppelingen", indexes={
 *
 *     @ORM\Index(name="discr", columns={"discr", "deleted"}),
 *     @ORM\Index(name="discr_2", columns={"discr", "deleted", "project_id"}),
 *     @ORM\Index(name="discr_3", columns={"discr", "deleted", "hulpvraagsoort_id"}),
 *     @ORM\Index(name="medewerker_id", columns={"medewerker_id", "discr", "deleted"})
 * })
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 *
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 *
 * @ORM\DiscriminatorMap({"hulpvraag" = "Hulpvraag", "hulpaanbod" = "Hulpaanbod"})
 *
 * @Gedmo\Loggable
 *
 * @Gedmo\SoftDeleteable
 */
abstract class Hulp
{
    use TimestampableTrait;

    public const DAGDEEL_OVERDAG = 'Overdag';
    public const DAGDEEL_AVOND = 'Avond';
    public const DAGDEEL_WEEKEND = 'Weekend';
    public const DAGDEEL_AVOND_WEEKEND = 'Avond/weekend';

    /**
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
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $einddatum;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $tussenevaluatiedatum;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $eindevaluatiedatum;

    /**
     * @ORM\Column(name="koppeling_startdatum", type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $koppelingStartdatum;

    /**
     * @ORM\Column(name="koppeling_einddatum", type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $koppelingEinddatum;

    /**
     * @ORM\Column(name="koppeling_succesvol", type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $koppelingSuccesvol;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project")
     *
     * @ORM\JoinColumn(name="project_id", nullable=false)
     *
     * @Gedmo\Versioned
     */
    protected $project;

    /**
     * @var EindeVraagAanbod
     *
     * @ORM\ManyToOne(targetEntity="EindeVraagAanbod")
     *
     * @ORM\JoinColumn(name="iz_vraagaanbod_id")
     *
     * @Gedmo\Versioned
     */
    protected $eindeVraagAanbod;

    /**
     * @var AfsluitredenKoppeling
     *
     * @ORM\ManyToOne(targetEntity="AfsluitredenKoppeling")
     *
     * @ORM\JoinColumn(name="iz_eindekoppeling_id")
     *
     * @Gedmo\Versioned
     */
    protected $afsluitredenKoppeling;

    /**
     * @var IzDeelnemer
     *
     * @ORM\ManyToOne(targetEntity="IzDeelnemer", inversedBy="koppelingen")
     *
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $izDeelnemer;

    /**
     * @var Verslag[]
     *
     * @ORM\OneToMany(targetEntity="Verslag", mappedBy="koppeling", cascade={"persist"})
     *
     * @ORM\OrderBy({"created": "desc"})
     */
    protected $verslagen;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $info;

    /**
     * @var Doelgroep[]
     *
     * @ORM\ManyToMany(targetEntity="Doelgroep")
     *
     * @ORM\JoinTable(
     *     name="iz_koppeling_doelgroep",
     *     joinColumns={@ORM\JoinColumn(name="koppeling_id")}
     * )
     */
    protected $doelgroepen;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    protected $dagdeel;

    /**
     * @var Geslacht
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geslacht")
     */
    protected $voorkeurGeslacht;

    /**
     * ORM mapping is defined in implementations (i.e. Hulpaanbod, Hulpvraag).
     *
     * @var Reservering
     */
    protected $reserveringen;

    /**
     * @var bool
     *
     * @ORM\Column(name="stagiair", type="boolean")
     */
    protected $stagiair = false;

    public function __construct()
    {
        $this->startdatum = new \DateTime('today');
        $this->verslagen = new ArrayCollection();
        $this->doelgroepen = new ArrayCollection();
        $this->reserveringen = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDeelnemer()
    {
        return $this->izDeelnemer;
    }

    /**
     * @deprecated
     */
    public function getIzDeelnemer()
    {
        return $this->izDeelnemer;
    }

    public function setDeelnemer(IzDeelnemer $izDeelnemer)
    {
        $this->izDeelnemer = $izDeelnemer;

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

    public function getProject()
    {
        return $this->project;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(?\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getTussenevaluatiedatum()
    {
        return $this->tussenevaluatiedatum;
    }

    public function getEindevaluatiedatum()
    {
        return $this->eindevaluatiedatum;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(?\DateTime $einddatum = null)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getKoppelingStartdatum()
    {
        return $this->koppelingStartdatum;
    }

    public function getKoppelingEinddatum()
    {
        return $this->koppelingEinddatum;
    }

    /**
     * @return AfsluitredenKoppeling
     */
    public function getAfsluitredenKoppeling()
    {
        return $this->afsluitredenKoppeling;
    }

    /**
     * @return EindeVraagAanbod
     */
    public function getEindeVraagAanbod()
    {
        return $this->eindeVraagAanbod;
    }

    public function setEindeVraagAanbod(EindeVraagAanbod $eindeVraagAanbod)
    {
        $this->eindeVraagAanbod = $eindeVraagAanbod;

        return $this;
    }

    public function isAfgesloten()
    {
        $now = new \DateTime();

        if ($this->isGekoppeld()) {
            return $this->getKoppelingEinddatum() instanceof \DateTime && $this->getKoppelingEinddatum() <= $now;
        }

        return $this->getEinddatum() instanceof \DateTime && $this->getEinddatum() <= $now;
    }

    /**
     * @return bool
     */
    public function isKoppelingSuccesvol()
    {
        return $this->koppelingSuccesvol;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo($info = null)
    {
        $this->info = $info;

        return $this;
    }

    public function getDagdeel()
    {
        return $this->dagdeel;
    }

    public function setDagdeel($dagdeel = null)
    {
        $this->dagdeel = $dagdeel;

        return $this;
    }

    public function getVoorkeurGeslacht()
    {
        return $this->voorkeurGeslacht;
    }

    public function setVoorkeurGeslacht(?Geslacht $geslacht = null)
    {
        $this->voorkeurGeslacht = $geslacht;

        return $this;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;
        $verslag->setKoppeling($this);
        $verslag->setIzDeelnemer($this->getDeelnemer());

        return $this;
    }

    public function isGereserveerd()
    {
        return $this->getHuidigeReservering() instanceof Reservering;
    }

    public function getHuidigeReservering()
    {
        $today = new \DateTime('today');
        foreach ($this->reserveringen as $reservering) {
            if ($today >= $reservering->getStartdatum()
                && $today <= $reservering->getEinddatum()
            ) {
                return $reservering;
            }
        }
    }

    public function isStagiair(): bool
    {
        return $this->stagiair;
    }

    public function setStagiair(bool $stagiair): void
    {
        $this->stagiair = $stagiair;
    }



    abstract public function getKoppeling();

    abstract public function setKoppeling(Koppeling $koppeling);

    public function isGekoppeld()
    {
        return $this->getKoppeling() instanceof Koppeling;
    }

    public function setTussenevaluatiedatum(?\DateTime $datum = null)
    {
        $this->tussenevaluatiedatum = $datum;
        $this->getKoppeling()->setTussenevaluatiedatum($datum);

        return $this;
    }

    public function setEindevaluatiedatum(?\DateTime $datum = null)
    {
        $this->eindevaluatiedatum = $datum;
        $this->getKoppeling()->setEindevaluatiedatum($datum);

        return $this;
    }

    public function setKoppelingEinddatum(?\DateTime $datum = null)
    {
        $this->koppelingEinddatum = $datum;
        $this->getKoppeling()->setEinddatum($datum);

        return $this;
    }

    public function setAfsluitredenKoppeling(?AfsluitredenKoppeling $afsluitreden)
    {
        $this->afsluitredenKoppeling = $afsluitreden;
        $this->getKoppeling()->setAfsluitreden($afsluitreden);

        return $this;
    }

    public function setKoppelingSuccesvol($succesvol)
    {
        $this->koppelingSuccesvol = (bool) $succesvol;
        $this->getKoppeling()->setSuccesvol($succesvol);

        return $this;
    }
}
