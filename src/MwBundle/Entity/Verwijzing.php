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
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=255)
 * @ORM\DiscriminatorMap({"Doorverwijzer" = "Doorverwijzing", "Trajecthouder" = "Trajecthouder"})
 * @Gedmo\Loggable
 */
abstract class Verwijzing
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    protected $startdatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $einddatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct()
    {
        $this->startdatum = new \DateTime();
    }

    public function __toString()
    {
        return (string) $this->naam;
    }

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
