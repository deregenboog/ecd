<?php

namespace ClipBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_contactmomenten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Contactmoment
{
    use TimestampableTrait;
    use OptionalBehandelaarTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @var Vraag
     *
     * @ORM\ManyToOne(targetEntity="Vraag", inversedBy="contactmomenten")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $vraag;

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

    public function __construct()
    {
        $this->datum = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOpmerking()
    {
        return $this->opmerking;
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;

        return $this;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getVraag()
    {
        return $this->vraag;
    }

    public function setVraag(Vraag $vraag)
    {
        $this->vraag = $vraag;

        return $this;
    }

    public function getClient()
    {
        return $this->vraag->getClient();
    }

    public function isDeletable()
    {
        return false;
    }
}
