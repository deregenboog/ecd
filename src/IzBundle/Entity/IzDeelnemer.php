<?php

namespace IzBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="Deelnemerstatus")
     * @Gedmo\Versioned
     */
    protected $status;

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
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ORM\OrderBy({"datum": "desc"})
     */
    protected $verslagen;

    /**
     * @var Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="iz_deelnemers_documenten", inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ORM\OrderBy({"created": "DESC"})
     */
    protected $documenten;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    protected $naamContactpersoon;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    protected $telefoonContactpersoon;

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

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
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
                && $koppelking->getKoppeling()->getStartdatum() <= $now
                && (is_null($koppelking->getKoppeling()->getAfsluitdatum()) || $koppelking->getKoppeling()->getAfsluitdatum() >= $now)
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

    public function getActieveKoppelingen()
    {
        return new ArrayCollection(array_filter(
            $this->getKoppelingen()->toArray(),
            function (Koppeling $koppeling) {
                return !$koppeling->isAfgesloten();
            }
        ));
    }

    public function hasActieveKoppelingen()
    {
        return count($this->getActieveKoppelingen()) > 0;
    }

    public function getAfgeslotenKoppelingen()
    {
        return new ArrayCollection(array_filter(
            $this->getKoppelingen()->toArray(),
            function (Koppeling $koppeling) {
                return $koppeling->isAfgesloten();
            }
        ));
    }

    /**
     * @return string
     */
    public function getNaamContactpersoon()
    {
        return $this->naamContactpersoon;
    }

    /**
     * @param string $naamContactpersoon
     */
    public function setNaamContactpersoon($naamContactpersoon = null)
    {
        $this->naamContactpersoon = $naamContactpersoon;

        return $this;
    }

    /**
     * @return string
     */
    public function getTelefoonContactpersoon()
    {
        return $this->telefoonContactpersoon;
    }

    /**
     * @param string $telefoonContactpersoon
     */
    public function setTelefoonContactpersoon($telefoonContactpersoon = null)
    {
        $this->telefoonContactpersoon = $telefoonContactpersoon;

        return $this;
    }
}
