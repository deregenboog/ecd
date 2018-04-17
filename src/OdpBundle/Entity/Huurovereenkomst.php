<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_huurovereenkomsten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Huurovereenkomst
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    public static function getVormChoices()
    {
        return [
            'Hospitaverhuur' => 'Hospitaverhuur',
            'Kostgangerschap' => 'Kostgangerschap',
            'Kleine schuld, grote winst' => 'Kleine schuld, grote winst',
            'Anders' => 'Anders',
        ];
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $opzegdatum;

    /**
     * @ORM\Column(name="opzegbrief_verstuurd", type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $opzegbriefVerstuurd = false;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @ORM\Column(length=50, nullable=true)
     * @Gedmo\Versioned
     */
    private $vorm;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var HuurovereenkomstAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="HuurovereenkomstAfsluiting", inversedBy="huurovereenkomsten", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluiting;

    /**
     * @var Huuraanbod
     * @ORM\OneToOne(targetEntity="Huuraanbod", inversedBy="huurovereenkomst")
     * @Gedmo\Versioned
     */
    private $huuraanbod;

    /**
     * @var Huurverzoek
     * @ORM\OneToOne(targetEntity="Huurverzoek", inversedBy="huurovereenkomst")
     * @Gedmo\Versioned
     */
    private $huurverzoek;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="odp_huurovereenkomst_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="odp_huurovereenkomst_document")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $documenten;

    public function __construct()
    {
        $this->startdatum = new \DateTime();

        $this->verslagen = new ArrayCollection();
        $this->documenten = new ArrayCollection();
    }

    public function __toString()
    {
        return implode(' - ', [$this->huurverzoek->getHuurder(), $this->huuraanbod->getVerhuurder()]);
    }

    public function isActief()
    {
        return null === $this->afsluiting;
    }

    public function getId()
    {
        return $this->id;
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

    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum(\DateTime $afsluitdatum = null)
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

    public function getHuuraanbod()
    {
        return $this->huuraanbod;
    }

    public function getHuurverzoek()
    {
        return $this->huurverzoek;
    }

    public function setHuuraanbod(Huuraanbod $huuraanbod)
    {
        $this->huuraanbod = $huuraanbod;

        return $this;
    }

    public function setHuurverzoek(Huurverzoek $huurverzoek)
    {
        $this->huurverzoek = $huurverzoek;

        return $this;
    }

    public function getHuurder()
    {
        return $this->huurverzoek->getHuurder();
    }

    public function getVerhuurder()
    {
        return $this->huuraanbod->getVerhuurder();
    }

    public function isDeletable()
    {
        return false;
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

    public function getOpzegdatum()
    {
        return $this->opzegdatum;
    }

    public function setOpzegdatum($opzegdatum)
    {
        $this->opzegdatum = $opzegdatum;

        return $this;
    }

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(HuurovereenkomstAfsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum($einddatum)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getVorm()
    {
        return $this->vorm;
    }

    public function setVorm($vorm)
    {
        $this->vorm = $vorm;
    }

    public function isOpzegbriefVerstuurd()
    {
        return $this->opzegbriefVerstuurd;
    }

    public function setOpzegbriefVerstuurd($opzegbriefVerstuurd)
    {
        $this->opzegbriefVerstuurd = (bool) $opzegbriefVerstuurd;

        return $this;
    }
}
