<?php

namespace ClipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_vragen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Vraag
{
    use RequiredBehandelaarTrait, TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $omschrijving;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="vragen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $client;

    /**
     * @var Vraagsoort
     *
     * @ORM\ManyToOne(targetEntity="Vraagsoort", inversedBy="vragen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $soort;

    /**
     * @var Hulpvrager
     *
     * @ORM\ManyToOne(targetEntity="Hulpvrager", inversedBy="vragen")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $hulpvrager;

    /**
     * @var Communicatiekanaal
     *
     * @ORM\ManyToOne(targetEntity="Communicatiekanaal", inversedBy="vragen")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $communicatiekanaal;

    /**
     * @var Leeftijdscategorie
     *
     * @ORM\ManyToOne(targetEntity="Leeftijdscategorie", inversedBy="vragen")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $leeftijdscategorie;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $startdatum;

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
    private $contactmomenten;

    public function __construct()
    {
        $this->documenten = new ArrayCollection();
        $this->contactmomenten = new ArrayCollection();

        $this->setStartdatum(new \DateTime());
        $this->setContactmoment(new Contactmoment());
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

    public function setBehandelaar(Behandelaar $behandelaar)
    {
        $this->behandelaar = $behandelaar;

        // initial Contactmoment has the same Behandelaar as this Vraag
        if (1 === count($this->contactmomenten)) {
            $this->contactmomenten[0]->setBehandelaar($behandelaar);
        }

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
     * Returns the initial contactmoment.
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

    public function isDeletable()
    {
        return false;
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
}
