<?php

namespace InloopBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="inloop_afsluiting_redenen")
 * @Gedmo\Loggable
 */
class RedenAfsluiting
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(nullable=false)
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $actief = true;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Gedmo\Versioned
     */
    private $gewicht = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $land = false;

    public function __toString()
    {
        return $this->naam;
    }

    public function getId()
    {
        return $this->id;
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

    public function isLand()
    {
        return $this->land;
    }

    public function setLand($land)
    {
        $this->land = $land;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }
}
