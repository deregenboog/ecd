<?php

namespace IzBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * deze class regelt de koppeling tussen vrijwilligers en trainingen.
 *
 * @ORM\Entity
 *
 * @ORM\Table(name="iz_deelnames")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Deelname
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var Training
     *
     * @ORM\ManyToOne(targetEntity="Training", cascade={"persist"}, inversedBy="deelnames")
     *
     * @ORM\JoinColumn(name="izTraining_id")
     *
     * @Gedmo\Versioned
     */
    private $training;

    /** @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $overig;

    /**
     * @var IzVrijwilliger
     *
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="trainingDeelnames")
     *
     * @ORM\JoinColumn(name="iz_vrijwilliger_id", nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $vrijwilliger;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date",nullable=true)
     */
    protected $datum;

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

    public function __construct(?IzVrijwilliger $vrijwilliger = null)
    {
        $this->vrijwilliger = $vrijwilliger;
    }

    public function getTraining()
    {
        return $this->training;
    }

    public function setTraining(Training $training)
    {
        $this->training = $training;

        return $this;
    }

    public function getIzVrijwilliger(): ?IzVrijwilliger
    {
        return $this->vrijwilliger;
    }

    public function setIzVrijwilliger(IzVrijwilliger $vrijwilliger): void
    {
        $this->vrijwilliger = $vrijwilliger;
    }

    public function getDatum(): ?\DateTime
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum): void
    {
        $this->datum = $datum;
    }

    public function getOverig(): ?string
    {
        return $this->overig;
    }

    public function setOverig(?string $overig): void
    {
        $this->overig = $overig;
    }
}
