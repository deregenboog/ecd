<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(name="deleted", type="datetime")
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
     * @ORM\OneToMany(targetEntity="Verslag", mappedBy="koppeling")
     * @ORM\OrderBy({"created": "desc"})
     */
    protected $verslagen;

    public function __construct()
    {
        $this->verslagen = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIzDeelnemer()
    {
        return $this->izDeelnemer;
    }

    public function setDeelnemer(IzDeelnemer $izDeelnemer)
    {
        $this->izKlant = $izDeelnemer;

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

    public function getEindeKoppeling()
    {
        return $this->eindeKoppeling;
    }

    public function isAfgesloten()
    {
        $now = new \DateTime();

        if ($this->isGekoppeld()) {
            return $this->getKoppelingEinddatum() instanceof \DateTime && $this->getKoppelingEinddatum() <= $now;
        }

        return $this->getEinddatum() instanceof \DateTime && $this->getEinddatum() <= $now;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }
}
