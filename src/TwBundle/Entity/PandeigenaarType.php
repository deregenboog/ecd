<?php

namespace TwBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_pandeigenaartype")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class PandeigenaarType
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
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @var ArrayCollection|Pandeigenaar[]
     *
     * @ORM\OneToMany(targetEntity="TwBundle\Entity\Pandeigenaar", mappedBy="pandeigenaarType")
     */
    private $pandeigenaars;

    /**
     * @ORM\Column(name="active", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $actief = true;

    public function __construct()
    {
        $this->pandeigenaars = new ArrayCollection();
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

    /**
     * @return ArrayCollection|Pandeigenaar[]
     */
    public function getPandeigenaars()
    {
        return $this->pandeigenaars;
    }

    /**
     * @param ArrayCollection|Pandeigenaar[] $pandeigenaars
     * @return PandeigenaarType
     */
    public function setPandeigenaars($pandeigenaars)
    {
        $this->pandeigenaars = $pandeigenaars;
        return $this;
    }

}
