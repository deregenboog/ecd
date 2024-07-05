<?php

namespace OekBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="oek_verwijzingen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 *
 * @ORM\DiscriminatorColumn(name="class", type="string")
 *
 * @ORM\DiscriminatorMap({
 *     "OekVerwijzingDoor" = "VerwijzingDoor",
 *     "OekVerwijzingNaar" = "VerwijzingNaar"
 * })
 *
 * @Gedmo\Loggable
 */
abstract class Verwijzing
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $actief = true;

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

    public function __toString()
    {
        return $this->naam;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    public function isActief()
    {
        return $this->actief;
    }

    public function setActief($actief)
    {
        $this->actief = $actief;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }
}
