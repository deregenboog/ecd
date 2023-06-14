<?php

namespace ClipBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_vragen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable
 */
class Vraag
{
    use OptionalBehandelaarTrait;
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $omschrijving;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="vragen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $client;

    /**
     * @var Vraagsoort
     *
     * @ORM\ManyToOne(targetEntity="Vraagsoort", inversedBy="vragen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $soort;

    /**
     * @var Hulpvrager
     *
     * @ORM\ManyToOne(targetEntity="Hulpvrager", inversedBy="vragen")
     * @Gedmo\Versioned
     */
    protected $hulpvrager;

    /**
     * @var Communicatiekanaal
     *
     * @ORM\ManyToOne(targetEntity="Communicatiekanaal", inversedBy="vragen")
     * @Gedmo\Versioned
     */
    protected $communicatiekanaal;

    /**
     * @var Leeftijdscategorie
     *
     * @ORM\ManyToOne(targetEntity="Leeftijdscategorie", inversedBy="vragen")
     * @Gedmo\Versioned
     */
    protected $leeftijdscategorie;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="clip_vraag_document")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $documenten;

    /**
     * @var ArrayCollection|Contactmoment[]
     *
     * @ORM\OneToMany(targetEntity="Contactmoment", mappedBy="vraag", cascade={"persist"})
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    protected $contactmomenten;

    /**
     * @var boolean
     * @ORM\Column(nullable=true)
     */
    protected $hulpCollegaGezocht = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct(?Contactmoment $contactmoment = null)
    {
        $this->documenten = new ArrayCollection();
        $this->contactmomenten = new ArrayCollection();

        $this->setStartdatum(new \DateTime());
        $contactmoment = ($contactmoment == null) ? new Contactmoment() : $contactmoment;
        $this->setContactmoment($contactmoment);
    }

    public function __toString()
    {
        return sprintf(
            '%s %s',
            $this->soort,
            $this->startdatum->format('d-m-Y')
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function setBehandelaar(?Behandelaar $behandelaar)
    {

        /**
         * issue #825 open vragen: geen behandelaar bij een open vraag dus.
         */
        // initial Contactmoment has the same Behandelaar as this Vraag
//        $t = count($this->contactmomenten);
//        $cm = $this->getContactmoment();
//        $cmin = is_null($cm->getBehandelaar());
//        $bin = is_null($behandelaar);
//        $c = count($this->contactmomenten);

//        if (1 === count($this->contactmomenten) && is_null($this->getContactmoment()->getBehandelaar()) && !is_null($behandelaar)) {
//
//            $this->contactmomenten[0]->setBehandelaar($behandelaar);
//
//        }

        $this->behandelaar = $behandelaar;


        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    public function getSoort()
    {
        return $this->soort;
    }

    public function setSoort(Vraagsoort $soort)
    {
        $this->soort = $soort;

        return $this;
    }

    public function getHulpvrager()
    {
        return $this->hulpvrager;
    }

    public function setHulpvrager(Hulpvrager $hulpvrager = null)
    {
        $this->hulpvrager = $hulpvrager;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum)
    {
        $this->startdatum = $startdatum;

        // initial Contactmoment has the same date as this Vraag
        if (1 === count($this->contactmomenten)) {
            $this->contactmomenten[0]->setDatum($startdatum);
        }

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

    public function getContactmomenten()
    {
        return $this->contactmomenten;
    }

    public function addContactmoment(Contactmoment $contactmoment)
    {
        $this->contactmomenten[] = $contactmoment;
        $contactmoment->setVraag($this);
        return $this;
    }

    /**
     * Returns the last contactmoment.
     *
     * @return Contactmoment
     */
    public function getContactmoment()
    {
        if (count($this->contactmomenten) > 0) {
            return $this->contactmomenten->last();
        }
    }

    /**
     * Sets the initial contactmoment.
     *
     * @param Contactmoment $contactmoment
     *
     * @return \ClipBundle\Entity\Vraag
     */
    public function setContactmoment(Contactmoment $contactmoment)
    {
        return $this->addContactmoment($contactmoment);
    }

    public function getLeeftijdscategorie()
    {
        return $this->leeftijdscategorie;
    }

    public function setLeeftijdscategorie(Leeftijdscategorie $leeftijdscategorie = null)
    {
        $this->leeftijdscategorie = $leeftijdscategorie;

        return $this;
    }

    public function isActief()
    {
        return null === $this->afsluitdatum;
    }

    /**
     * SoftDeleteable, so it's safe to return true.
     *
     * @return bool
     */
    public function isDeletable()
    {
        return true;
    }

    public function getCommunicatiekanaal()
    {
        return $this->communicatiekanaal;
    }

    public function setCommunicatiekanaal(Communicatiekanaal $communicatiekanaal = null)
    {
        $this->communicatiekanaal = $communicatiekanaal;

        return $this;
    }

    public function getOmschrijving()
    {
        return $this->omschrijving;
    }

    public function setOmschrijving($omschrijving)
    {
        $this->omschrijving = $omschrijving;

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

    public function heropen()
    {
        $this->afsluitdatum = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHulpCollegaGezocht(): ?bool
    {
        return $this->hulpCollegaGezocht;
    }

    /**
     * @param bool $hulpCollegaGezocht
     */
    public function setHulpCollegaGezocht(bool $hulpCollegaGezocht): void
    {
        $this->hulpCollegaGezocht = $hulpCollegaGezocht;
    }
}
