<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_deelnemers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Deelnemer
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     * @Gedmo\Versioned
     */
    private $risDossierNummer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="aanmelddatum", type="date")
     * @Gedmo\Versioned
     */
    private $aanmelddatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="afsluitdatum", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var Afsluiting
     *
     * @ORM\ManyToOne(targetEntity="Afsluiting", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluiting;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $documenten;

    /**
     * @var ArrayCollection|Traject[]
     *
     * @ORM\OneToMany(targetEntity="Traject", mappedBy="deelnemer", cascade={"persist"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $trajecten;

    public function __construct()
    {
        $this->aanmelddatum = new \DateTime();

        $this->verslagen = new ArrayCollection();
        $this->documenten = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->klant;
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

    public function isActief()
    {
        return $this->afsluiting === null;
    }

    public function isDeletable()
    {
        return 0 === count($this->trajecten)
            && 0 === count($this->documenten)
            && 0 === count($this->verslagen);
    }

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(DeelnemerAfsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }

    public function getTrajecten()
    {
        return $this->trajecten;
    }

    public function setTrajecten($trajecten)
    {
        $this->trajecten = $trajecten;

        return $this;
    }
}
