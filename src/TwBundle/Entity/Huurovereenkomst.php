<?php

namespace TwBundle\Entity;

use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="tw_huurovereenkomsten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Huurovereenkomst
{
    use TimestampableTrait;
    use RequiredMedewerkerTrait;


    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $opzegdatum;

    /**
     * @ORM\Column(name="opzegbrief_verstuurd", type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $opzegbriefVerstuurd = false;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @var VormVanOvereenkomst
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\VormVanOvereenkomst",cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    private $vormVanOvereenkomst;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var HuurovereenkomstAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="HuurovereenkomstAfsluiting", inversedBy="huurovereenkomsten", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    private $afsluiting;

    /**
     * @var Huuraanbod
     *
     * @ORM\OneToOne(targetEntity="Huuraanbod", inversedBy="huurovereenkomst")
     *
     * @Gedmo\Versioned
     */
    private $huuraanbod;

    /**
     * @var Huurverzoek
     *
     * @ORM\OneToOne(targetEntity="Huurverzoek", inversedBy="huurovereenkomst")
     *
     * @Gedmo\Versioned
     */
    private $huurverzoek;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"},fetch="LAZY")
     *
     * @ORM\JoinTable(name="tw_huurovereenkomst_verslag",
     *      joinColumns={@ORM\JoinColumn(name="huurovereenkomst_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="verslag_id", referencedColumnName="id")}
     *     )
     */
    private $verslagen;

    /**
     * @var ArrayCollection|FinancieelVerslag[]
     *
     * @ORM\ManyToMany(targetEntity="FinancieelVerslag", cascade={"persist"}, fetch="LAZY")
     *
     * @ORM\JoinTable(name="tw_huurovereenkomst_finverslag",
     *      joinColumns={@ORM\JoinColumn(name="huurovereenkomst_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="verslag_id", referencedColumnName="id")}
     *     )
     */
    private $financieleverslagen;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"}, fetch="LAZY")
     *
     * @ORM\JoinTable(name="tw_huurovereenkomst_document",
     *     joinColumns={@ORM\JoinColumn(name="huurovereenkomst_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")}
     * )
     */
    private $documenten;

    /**
     * @var ArrayCollection|FinancieelDocument[]
     *
     * @ORM\ManyToMany(targetEntity="FinancieelDocument", cascade={"persist"})
     *
     * @ORM\JoinTable(name="tw_huurovereenkomst_findocument",
     *      joinColumns={@ORM\JoinColumn(name="huurovereenkomst_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")}
     *     )
     */
    private $financieledocumenten;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned()
     */
    private $isReservering = false;

    /**
     * @var HuurovereenkomstType
     *
     * @ORM\ManyToOne(targetEntity="HuurovereenkomstType", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    private $huurovereenkomstType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;


    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    protected $huurderAVP;

    public function __construct()
    {
        $this->startdatum = new \DateTime();
        //        $this->einddatum = new \DateTime("+two months");
        $this->verslagen = new ArrayCollection();
        $this->documenten = new ArrayCollection();
    }

    public function __toString()
    {
        return implode(' - ', [$this->huurverzoek->getKlant(), $this->huuraanbod->getVerhuurder()]);
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

    public function setStartdatum(?\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum(?\DateTime $afsluitdatum = null)
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

    public function getKlant()
    {
        return $this->huurverzoek->getKlant();
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

    /**
     * @return ArrayCollection|FinancieelDocument[]
     */
    public function getFinancieledocumenten()
    {
        return $this->financieledocumenten;
    }

    /**
     * @param ArrayCollection|FinancieelDocument[] $financieledocumenten
     */
    public function setFinancieledocumenten($financieledocumenten): void
    {
        $this->financieledocumenten = $financieledocumenten;
    }

    public function addFinancieelDocument(FinancieelDocument $document)
    {
        $this->financieledocumenten[] = $document;

        return $this;
    }

    /**
     * @return ArrayCollection|FinancieelVerslag[]
     */
    public function getFinancieleverslagen()
    {
        return $this->financieleverslagen;
    }

    /**
     * @param ArrayCollection|FinancieelVerslag[] $financieleverslagen
     */
    public function setFinancieleverslagen($financieleverslagen): void
    {
        $this->financieleverslagen = $financieleverslagen;
    }

    public function addFinancieelVerslag(FinancieelVerslag $verslag)
    {
        $this->financieleverslagen[] = $verslag;

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

    public function isOpzegbriefVerstuurd()
    {
        return $this->opzegbriefVerstuurd;
    }

    public function setOpzegbriefVerstuurd($opzegbriefVerstuurd)
    {
        $this->opzegbriefVerstuurd = (bool) $opzegbriefVerstuurd;

        return $this;
    }

    public function isReservering(): bool
    {
        return $this->isReservering;
    }

    public function setIsReservering(bool $isReservering): void
    {
        $this->isReservering = $isReservering;
    }

    public function getHuurovereenkomstType(): ?HuurovereenkomstType
    {
        return $this->huurovereenkomstType;
    }

    public function setHuurovereenkomstType(HuurovereenkomstType $huurovereenkomstType): Huurovereenkomst
    {
        $this->huurovereenkomstType = $huurovereenkomstType;

        return $this;
    }

    public function getVormVanOvereenkomst(): ?VormVanOvereenkomst
    {
        return $this->vormVanOvereenkomst;
    }

    public function setVormVanOvereenkomst(?VormVanOvereenkomst $vormVanOvereenkomst): void
    {
        $this->vormVanOvereenkomst = $vormVanOvereenkomst;
    }

    public function getHuurderAVP(): ?bool
    {
        return $this->huurderAVP;
    }

    public function setHuurderAVP(?bool $huurderAVP): void
    {
        $this->huurderAVP = $huurderAVP;
    }
}
