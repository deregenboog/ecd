<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_hulp")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"hulpvraag" = "Hulpvraag", "hulpaanbod" = "Hulpaanbod"})
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable
 */
abstract class Hulp
{
    use TimestampableTrait;

    const DAGEN = [
        'maandag' => 0b0000001,
        'dinsdag' => 0b0000010,
        'woensdag' => 0b0000100,
        'donderdag' => 0b0001000,
        'vrijdag' => 0b0010000,
        'zaterdag' => 0b0100000,
        'zondag' => 0b1000000,
    ];

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
     * @var AfsluitredenKoppeling
     *
     * @ORM\ManyToOne(targetEntity="AfsluitredenKoppeling")
     * @ORM\JoinColumn(name="iz_eindekoppeling_id")
     * @Gedmo\Versioned
     */
    protected $afsluitredenKoppeling;

    /**
     * @var IzDeelnemer
     * @ORM\ManyToOne(targetEntity="IzDeelnemer", inversedBy="koppelingen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $izDeelnemer;

    /**
     * @var Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ORM\OrderBy({"datum": "desc"})
     */
    protected $verslagen;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $info;

    /**
     * @var Werkgebied[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Werkgebied")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(referencedColumnName="naam")})
     * @Assert\Count(min=1, minMessage="Selecteer tenminste één stadsdeel.")
     */
    protected $voorkeurStadsdelen;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\Versioned
     * @Assert\GreaterThan(0, message="Selecteer tenminste één dag.")
     */
    protected $beschikbareDagen = 0;

    /**
     * @var Geslacht
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geslacht")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $voorkeurGeslacht;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    protected $voorkeurNietRoker = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    protected $voorkeurGeenDieren = false;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    protected $voorkeurMinLeeftijd;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    protected $voorkeurMaxLeeftijd;

    public function __construct()
    {
        $this->startdatum = new \DateTime('today');
        $this->verslagen = new ArrayCollection();
        $this->voorkeurStadsdelen = new ArrayCollection();
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

    public function isGekoppeld()
    {
        return $this->koppeling instanceof Koppeling;
    }

    public function getKoppeling()
    {
        return $this->koppeling;
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
            return $this->getKoppeling()->getAfsluitdatum() instanceof \DateTime
                && $this->getKoppeling()->getAfsluitdatum() <= $now;
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

    public function getBeschikbareDagen($asInt = false)
    {
        if ($asInt) {
            return $this->beschikbareDagen;
        }

        $dagen = [];

        foreach (self::DAGEN as $name => $value) {
            if ($this->beschikbareDagen & $value) {
                $dagen[] = $name;
            }
        }

        return $dagen;
    }

    public function setBeschikbareDagen(array $beschikbareDagen)
    {
        $dagen = 0;

        foreach (self::DAGEN as $name => $value) {
            if (in_array($name, $beschikbareDagen)) {
                $dagen += $value;
            }
        }

        $this->beschikbareDagen = $dagen;

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

    public function getVoorkeurStadsdelen()
    {
        return $this->voorkeurStadsdelen;
    }

    public function setVoorkeurStadsdelen(array $stadsdelen = [])
    {
        $this->voorkeurStadsdelen = $stadsdelen;

        return $this;
    }

    public function isVoorkeurNietRoker()
    {
        return $this->voorkeurNietRoker;
    }

    public function setVoorkeurNietRoker($voorkeur)
    {
        $this->voorkeurNietRoker = (bool) $voorkeur;

        return $this;
    }

    public function isVoorkeurGeenDieren()
    {
        return $this->voorkeurGeenDieren;
    }

    public function setVoorkeurGeenDieren($voorkeur)
    {
        $this->voorkeurGeenDieren = (bool) $voorkeur;

        return $this;
    }

    /**
     * @return number
     */
    public function getVoorkeurMinLeeftijd()
    {
        return $this->voorkeurMinLeeftijd;
    }

    /**
     * @param number $voorkeurMinLeeftijd
     */
    public function setVoorkeurMinLeeftijd($voorkeurMinLeeftijd)
    {
        $this->voorkeurMinLeeftijd = $voorkeurMinLeeftijd;

        return $this;
    }

    /**
     * @return number
     */
    public function getVoorkeurMaxLeeftijd()
    {
        return $this->voorkeurMaxLeeftijd;
    }

    /**
     * @param number $voorkeurMaxLeeftijd
     */
    public function setVoorkeurMaxLeeftijd($voorkeurMaxLeeftijd)
    {
        $this->voorkeurMaxLeeftijd = $voorkeurMaxLeeftijd;

        return $this;
    }
}
