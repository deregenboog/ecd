<?php

namespace TwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_vormvanovereenkomst")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class VormVanOvereenkomst
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $label;

    /**
     * @var Huuraanbod[]
     * @ORM\OneToMany(targetEntity="Huuraanbod", mappedBy="vormvanovereenkomst")
     */
    private $huuraanbod;


    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $enddate;




    public function __construct()
    {
        $this->startdate = new \DateTime();
    }

    public function __toString()
    {
        return $this->label;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return \DateTime
     */
    public function getStartdate(): \DateTime
    {
        return $this->startdate;
    }

    /**
     * @param \DateTime $startdate
     */
    public function setStartdate(\DateTime $startdate): void
    {
        $this->startdate = $startdate;
    }

    /**
     * @return mixed
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * @param mixed $enddate
     */
    public function setEnddate(?\DateTime $enddate): void
    {
        $this->enddate = $enddate;
    }

    /**
     * @return Huuraanbod[]
     */
    public function getHuuraanbod(): ?array
    {
        return $this->huuraanbod;
    }

    /**
     * @param Huuraanbod[] $huuraanbod
     */
    public function setHuuraanbod(array $huuraanbod): void
    {
        $this->huuraanbod = $huuraanbod;
    }
}
