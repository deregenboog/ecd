<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
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
    private $risDossiernummer;

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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="dagbesteding_deelnemer_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="dagbesteding_deelnemer_document")
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

    /**
     * @var ArrayCollection|Contactpersoon[]
     *
     * @ORM\OneToMany(targetEntity="Contactpersoon", mappedBy="deelnemer", cascade={"persist"})
     * @ORM\OrderBy({"soort" = "DESC"})
     */
    private $contactpersonen;

    public function __construct()
    {
        $this->aanmelddatum = new \DateTime();

        $this->trajecten = new ArrayCollection();
        $this->contactpersonen = new ArrayCollection();
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

    public function setAfsluitdatum(\DateTime $afsluitdatum = null)
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
        return null === $this->afsluiting;
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

    public function setAfsluiting(Deelnemerafsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }

    public function getTrajecten()
    {
        return $this->trajecten;
    }

    public function addTraject(Traject $traject)
    {
        $this->trajecten[] = $traject;
        $traject->setDeelnemer($this);

        return $this;
    }

    public function getRisDossiernummer()
    {
        return $this->risDossiernummer;
    }

    public function setRisDossiernummer($risDossiernummer)
    {
        $this->risDossiernummer = $risDossiernummer;

        return $this;
    }

    public function getContactpersonen()
    {
        return $this->contactpersonen;
    }

    public function addContactpersoon(Contactpersoon $contactpersoon)
    {
        $this->contactpersonen[] = $contactpersoon;
        $contactpersoon->setDeelnemer($this);

        return $this;
    }

    public function reopen()
    {
        $this->afsluitdatum = null;
        $this->afsluiting = null;

        return $this;
    }
}
