<?php

namespace IzBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_ontstaan_contacten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class ContactOntstaan
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(name="active", type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $actief = true;

    public function __toString()
    {
        return $this->getNaam();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function isActief()
    {
        return $this->actief;
    }
}
