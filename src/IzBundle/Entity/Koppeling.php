<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Geslacht;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_koppelingen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"hulpvraag" = "Hulpvraag", "hulpaanbod" = "Hulpaanbod"})
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable
 */
abstract class Koppeling
{
    const DAGDEEL_OVERDAG = 'Overdag';
    const DAGDEEL_AVOND = 'Avond';
    const DAGDEEL_WEEKEND = 'Weekend';
    const DAGDEEL_AVOND_WEEKEND = 'Avond/weekend';

    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $einddatum;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $tussenevaluatiedatum;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $eindevaluatiedatum;

    /**
     * @ORM\Column(name="koppeling_startdatum", type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $koppelingStartdatum;

    /**
     * @ORM\Column(name="koppeling_einddatum", type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $koppelingEinddatum;

    /**
     * @ORM\Column(name="koppeling_succesvol", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $koppelingSuccesvol;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $project;

    /**
     * @var EindeVraagAanbod
     * @ORM\ManyToOne(targetEntity="EindeVraagAanbod")
     * @ORM\JoinColumn(name="iz_vraagaanbod_id")
     * @Gedmo\Versioned
     */
    protected $eindeVraagAanbod;

    /**
     * @var EindeKoppeling
     * @ORM\ManyToOne(targetEntity="EindeKoppeling")
     * @ORM\JoinColumn(name="iz_eindekoppeling_id")
     * @Gedmo\Versioned
     */
    protected $eindeKoppeling;

    /**
     * @var IzDeelnemer
     * @ORM\ManyToOne(targetEntity="IzDeelnemer", inversedBy="koppelingen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $izDeelnemer;

    /**
     * @var Verslag[]
     * @ORM\OneToMany(targetEntity="Verslag", mappedBy="koppeling", cascade={"persist"})
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
     * @ORM\JoinColumn(nullable=true)
     */
    protected $voorkeurGeslacht;

    public function __construct()
    {
        $this->startdatum = new \DateTime('today');
        $this->verslagen = new ArrayCollection();
        $this->doelgroepen = new ArrayCollection();
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

    public function setStartdatum(\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getTussenevaluatiedatum()
    {
        return $this->tussenevaluatiedatum;
    }

    public function setTussenevaluatiedatum(\DateTime $datum = null)
    {
        $this->tussenevaluatiedatum = $datum;

        return $this;
    }

    public function getEindevaluatiedatum()
    {
        return $this->eindevaluatiedatum;
    }

    public function setEindevaluatiedatum(\DateTime $datum = null)
    {
        $this->eindevaluatiedatum = $datum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(\DateTime $einddatum = null)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getKoppelingStartdatum()
    {
        return $this->koppelingStartdatum;
    }

    public function setKoppelingStartdatum(\DateTime $koppelingStartdatum = null)
    {
        $this->koppelingStartdatum = $koppelingStartdatum;

        if ($koppelingStartdatum && !$this->tussenevaluatiedatum) {
            $this->tussenevaluatiedatum = clone $koppelingStartdatum;
            $this->tussenevaluatiedatum->modify('+3 months');
        }

        if ($koppelingStartdatum && !$this->eindevaluatiedatum) {
            $this->eindevaluatiedatum = clone $koppelingStartdatum;
            $this->eindevaluatiedatum->modify('+6 months');
        }

        return $this;
    }

    public function getKoppelingEinddatum()
    {
        return $this->koppelingEinddatum;
    }

    public function setKoppelingEinddatum(\DateTime $koppelingEinddatum = null)
    {
        $this->koppelingEinddatum = $koppelingEinddatum;

        return $this;
    }

    /**
     * @return EindeKoppeling
     */
    public function getEindeKoppeling()
    {
        return $this->eindeKoppeling;
    }

    /**
     * @return IzEindeVraagAanbod
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

    public function setVoorkeurGeslacht(Geslacht $geslacht = null)
    {
        $this->voorkeurGeslacht = $geslacht;

        return $this;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;
        $verslag->setKoppeling($this);
        $verslag->setIzDeelnemer($this->getDeelnemer());
    }
}
