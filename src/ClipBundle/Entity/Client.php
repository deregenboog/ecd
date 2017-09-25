<?php

namespace ClipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;
use AppBundle\Model\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_clienten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Client
{
    use TimestampableTrait, RequiredBehandelaarTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="aanmelddatum", type="date")
     * @Gedmo\Versioned
     */
    private $aanmelddatum;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="clip_client_document")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $documenten;

    /**
     * @var ArrayCollection|Vraag[]
     *
     * @ORM\OneToMany(targetEntity="Vraag", mappedBy="client", cascade={"persist"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $vragen;

    /**
     * @var Viacategorie
     *
     * @ORM\ManyToOne(targetEntity="Viacategorie", inversedBy="clienten")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $viacategorie;

    public function __construct()
    {
        $this->aanmelddatum = new \DateTime();

        $this->contactmomenten = new ArrayCollection();
        $this->documenten = new ArrayCollection();
        $this->vragen = new ArrayCollection();
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

    public function getAanmelddatum()
    {
        return $this->aanmelddatum;
    }

    public function setAanmelddatum($aanmelddatum)
    {
        $this->aanmelddatum = $aanmelddatum;

        return $this;
    }

    public function getContactmomenten()
    {
        $contactmomenten = [];

        foreach ($this->vragen as $vraag) {
            foreach ($vraag->getContactmomenten() as $contactmoment) {
                $contactmomenten[] = $contactmoment;
            }
        }

        usort($contactmomenten, function (Contactmoment $contactmoment1, Contactmoment $contactmoment2) {
            if ($contactmoment1->getDatum() > $contactmoment2->getDatum()) {
                return -1;
            } elseif ($contactmoment1->getDatum() < $contactmoment2->getDatum()) {
                return 1;
            } else {
                return 0;
            }
        });

        return $contactmomenten;
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

    public function getVragen()
    {
        return $this->vragen;
    }

    public function addVraag(Vraag $vraag)
    {
        $this->vragen[] = $vraag;
        $vraag->setClient($this);

        return $this;
    }

    public function getViacategorie()
    {
        return $this->viacategorie;
    }

    public function setViacategorie(Viacategorie $viacategorie)
    {
        $this->viacategorie = $viacategorie;

        return $this;
    }

    public function isDeletable()
    {
        return 0 === count($this->documenten)
            && 0 === count($this->vragen)
        ;
    }
}
