<?php

namespace InloopBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * deze class regelt de koppeling tussen vrijwilligers en trainingen.
 *
 * @ORM\Entity
 *
 * @ORM\Table(name="inloop_deelnames")
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
     * @ORM\JoinColumn(name="inloopTraining_id")
     *
     * @Gedmo\Versioned
     */
    private $training;

    /** @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $overig;

    /**
     * @var Vrijwilliger
     *
     * @ORM\ManyToOne(targetEntity="Vrijwilliger", inversedBy="trainingDeelnames")
     *
     * @ORM\JoinColumn(name="inloop_vrijwilliger_id")
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

    public function __construct(?Vrijwilliger $vrijwilliger = null)
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

    public function getVrijwilliger(): ?Vrijwilliger
    {
        return $this->vrijwilliger;
    }

    public function setVrijwilliger(Vrijwilliger $vrijwilliger): void
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
