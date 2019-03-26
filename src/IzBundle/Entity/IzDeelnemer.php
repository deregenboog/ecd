<?php

namespace IzBundle\Entity;

use AppBundle\Model\OptionalMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="iz_deelnemers",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_model_foreign_key_idx", columns={"model", "foreign_key"})}
 * )
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({"Klant" = "IzKlant", "Vrijwilliger" = "IzVrijwilliger"})
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable
 */
abstract class IzDeelnemer
{
    use TimestampableTrait, OptionalMedewerkerTrait;

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
     * @var ArrayCollection|Hulp[]
     * @ORM\OneToMany(targetEntity="Hulp", mappedBy="izDeelnemer")
     */
    private $koppelingen;

    /**
     * @var Intake
     * @ORM\OneToOne(targetEntity="Intake", mappedBy="izDeelnemer", cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $intake;

    /**
     * @ORM\Column(name="datum_aanmelding", type="date")
     * @Gedmo\Versioned
     */
    protected $datumAanmelding;

    /**
     * @var Project[]
     * @ORM\ManyToMany(targetEntity="Project")
     * @ORM\JoinTable(
     *     name="iz_deelnemers_iz_projecten",
     *     joinColumns={@ORM\JoinColumn(name="iz_deelnemer_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="iz_project_id")}
     * )
     */
    protected $projecten;

    /**
     * @ORM\Column(name="datumafsluiting", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $afsluitDatum;

    /**
     * @var Afsluiting
     * @ORM\ManyToOne(targetEntity="Afsluiting")
     * @ORM\JoinColumn(name="iz_afsluiting_id")
     * @Gedmo\Versioned
     */
    protected $afsluiting;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $notitie;

    /**
     * @var Verslag[]
     *
     * @ORM\OneToMany(targetEntity="Verslag", mappedBy="izDeelnemer", cascade={"persist"})
     * @ORM\JoinColumn(name="iz_verslagen")
     * @ORM\OrderBy({"created"="desc"})
     */
    protected $verslagen;

    /**
     * @var Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="iz_deelnemers_documenten", inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE", unique=true)})
     * @ORM\OrderBy({"created": "DESC"})
     */
    protected $documenten;

    public function __construct()
    {
        $this->koppelingen = new ArrayCollection();
        $this->projecten = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
        $this->documenen = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(Afsluiting $afsluiting = null)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }

    public function getIntake()
    {
        return $this->intake;
    }

    public function setIntake(Intake $intake)
    {
        $this->intake = $intake;
        $intake->setIzDeelnemer($this);

        return $this;
    }

    public function getProjecten()
    {
        return $this->projecten;
    }

    public function setProjecten(ArrayCollection $projecten)
    {
        $this->projecten = $projecten;

        return $this;
    }

    public function getAfsluitDatum()
    {
        return $this->afsluitDatum;
    }

    public function setAfsluitDatum(\DateTime $afsluitdatum = null)
    {
        $this->afsluitDatum = $afsluitdatum;

        return $this;
    }

    public function isGekoppeld()
    {
        $now = new \DateTime();
        foreach ($this->koppelingen as $koppelking) {
            if ($koppelking->isGekoppeld()
                && $koppelking->getKoppelingStartdatum() <= $now
                && (is_null($koppelking->getKoppelingEinddatum()) || $koppelking->getKoppelingEinddatum() >= $now)
            ) {
                return true;
            }
        }

        return false;
    }

    public function getDatumAanmelding()
    {
        return $this->datumAanmelding;
    }

    /**
     * @param \DateTime $datum
     */
    public function setDatumAanmelding(\DateTime $datum)
    {
        $this->datumAanmelding = $datum;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotitie()
    {
        return $this->notitie;
    }

    /**
     * @param string $notitie
     */
    public function setNotitie($notitie)
    {
        $this->notitie = $notitie;

        return $this;
    }

    public function getVerslagen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('koppeling'))
            ->orderBy(['created' => 'desc'])
        ;

        return $this->verslagen->matching($criteria);
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;
        $verslag->setIzDeelnemer($this);

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

    public function isAfgesloten()
    {
        return $this->afsluitDatum || $this->afsluiting;
    }

    public function reopen()
    {
        $this->afsluitDatum = null;
        $this->afsluiting = null;

        return $this;
    }
}
