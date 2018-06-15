<?php

namespace MwBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="doorverwijzers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Doorverwijzer
{
    use IdentifiableTrait, TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @return string
     */
    public function getNaam()
    {
        return $this->naam;
    }

    /**
     * @param string $naam
     */
    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartdatum()
    {
        return $this->startdatum;
    }

    /**
     * @param \DateTime $startdatum
     */
    public function setStartdatum($startdatum)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEinddatum()
    {
        return $this->einddatum;
    }

    /**
     * @param \DateTime $einddatum
     */
    public function setEinddatum($einddatum)
    {
        $this->einddatum = $einddatum;

        return $this;
    }
}
