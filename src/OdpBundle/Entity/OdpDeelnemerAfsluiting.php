<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_deelnemer_afsluitingen")
 * @ORM\HasLifecycleCallbacks
 */
class OdpDeelnemerAfsluiting
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $naam;

    /**
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $actief;

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
