<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_via_personen")
 * @ORM\HasLifecycleCallbacks
 */
class IzViaPersoon
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $naam;

    /**
     * @ORM\Column(name="active", type="boolean", nullable=false)
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
