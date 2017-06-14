<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\OptionalMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_rapportages")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Rapportage
{
    use TimestampableTrait, OptionalMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Traject
     * @ORM\ManyToOne(targetEntity="Traject", inversedBy="rapportages")
     * @Gedmo\Versioned
     */
    private $traject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="dagbesteding_rapportage_document")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $documenten;

    public function __construct(\DateTime $datum = null)
    {
        $this->datum = $datum;

        $this->documenten = new ArrayCollection();
    }

    public function __toString()
    {
        return 'Rapportage van '.$this->datum->format('d-m-Y');
    }

    public function getStatus()
    {
        if (count($this->documenten) > 0) {
            return 'done';
        }

        if ($this->datum < new \DateTime()) {
            return 'late';
        }

        if ($this->datum < new \DateTime('+1 month')) {
            return 'almost';
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTraject()
    {
        return $this->traject;
    }

    public function setTraject(Traject $traject)
    {
        $this->traject = $traject;

        return $this;
    }

    public function getDatum()
    {
        return $this->datum;
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

    public function isDeletable()
    {
        return 0 === count($this->documenten);
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }
}
