<?php

namespace TwBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_deelnames")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 *
 * deze class regelt de koppeling tussen vrijwilligers en trainingen
 */
class Deelname
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Training
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Training", cascade={"persist"})
     * @ORM\JoinColumn(name="mwTraining_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $training;

    /** @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $overig = null;

    /**
     * @var Vrijwilliger
     *
     * @ORM\ManyToOne(targetEntity="Vrijwilliger", inversedBy="trainingDeelnames")
     * @ORM\JoinColumn(name="tw_vrijwilliger_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $vrijwilliger;

    /**
     * @var \DateTime
     * @ORM\Column(type="date",nullable=true)
     */
    protected $datum;

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

    public function __construct(Vrijwilliger $vrijwilliger = null)
    {
        $this->vrijwilliger = $vrijwilliger;
    }

    public function getId()
    {
        return $this->id;
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

    /**
     * @return Vrijwilliger
     */
    public function getVrijwilliger(): ?Vrijwilliger
    {
        return $this->vrijwilliger;
    }

    /**
     * @param Vrijwilliger $vrijwilliger
     */
    public function setVrijwilliger(Vrijwilliger $vrijwilliger): void
    {
        $this->vrijwilliger = $vrijwilliger;
    }

    /**
     * @return \DateTime
     */
    public function getDatum(): ?\DateTime
    {
        return $this->datum;
    }

    /**
     * @param \DateTime $datum
     */
    public function setDatum(\DateTime $datum): void
    {
        $this->datum = $datum;
    }

    /**
     * @return string
     */
    public function getOverig(): ?string
    {
        return $this->overig;
    }

    /**
     * @param string $overig
     */
    public function setOverig(?string $overig): void
    {
        $this->overig = $overig;
    }
}
