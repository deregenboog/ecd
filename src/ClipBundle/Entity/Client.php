<?php

namespace ClipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\PersonTrait;
use AppBundle\Model\AddressTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_clienten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Client
{
    use TimestampableTrait, RequiredBehandelaarTrait, PersonTrait, AddressTrait;

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
     * @ORM\Column(name="regipro_volgnr", nullable=true)
     */
    private $regiproVolgnr;

    /**
     * @var string
     *
     * @ORM\Column(name="regipro_person_id", nullable=true)
     */
    private $regiproPersonId;

    /**
     * @var string
     *
     * @ORM\Column(name="regipro_client_id", nullable=true)
     */
    private $regiproClientId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="aanmelddatum", type="date")
     * @Gedmo\Versioned
     */
    private $aanmelddatum;

    /**
     * @var string
     *
     * @ORM\Column(name="etniciteit", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $etniciteit;

    /**
     * @var Viacategorie
     *
     * @ORM\ManyToOne(targetEntity="Viacategorie", inversedBy="clienten")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $viacategorie;

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

    public function __construct()
    {
        $this->aanmelddatum = new \DateTime();

        $this->documenten = new ArrayCollection();
        $this->vragen = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getEtniciteit()
    {
        return $this->etniciteit;
    }

    public function setEtniciteit($etniciteit = null)
    {
        $this->etniciteit = $etniciteit;

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

    public function setViacategorie(Viacategorie $viacategorie = null)
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

    public function getRegiproVolgnr()
    {
        return $this->regiproVolgnr;
    }

    public function setRegiproVolgnr($regiproVolgnr)
    {
        $this->regiproVolgnr = $regiproVolgnr;

        return $this;
    }

    public function setRegiproClientId($regiproClientId)
    {
        $this->regiproClientId = $regiproClientId;

        return $this;
    }

    public function setRegiproPersonId($regiproPersonId)
    {
        $this->regiproPersonId = $regiproPersonId;

        return $this;
    }

}
