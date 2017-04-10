<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="odp_woningbouwcorporaties")
 * @ORM\HasLifecycleCallbacks
 */
class Woningbouwcorporatie
{
    use TimestampableTrait;

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
    private $actief = true;

    /**
     * @var ArrayCollection|Verhuurder[]
     *
     * @ORM\OneToMany(targetEntity="Verhuurder", mappedBy="woningbouwcorporatie")
     */
    private $verhuurders;

    public function __construct()
    {
        $this->verhuurders = new ArrayCollection();
    }

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
        return 0 === count($this->verhuurders);
    }
}
