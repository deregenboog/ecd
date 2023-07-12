<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_verslagen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verslag
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @var Deelnemer
     * @ORM\ManyToOne(targetEntity="DagbestedingBundle\Entity\Deelnemer",inversedBy="verslagen")
     */
    private $deelnemer;

    /**
     * @var int
     * @ORM\Column()
     * @deprecated Can be removed after succesful migration of data // 20230712 JTB
     */
    private $traject_id;

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

    public function getOpmerking()
    {
        return utf8_decode($this->opmerking);
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = utf8_encode($opmerking);

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

    /**
     * @return Deelnemer
     */
    public function getDeelnemer(): Deelnemer
    {
        return $this->deelnemer;
    }

    /**
     * @param Deelnemer $deelnemer
     */
    public function setDeelnemer(Deelnemer $deelnemer): void
    {
        $this->deelnemer = $deelnemer;
    }


}
