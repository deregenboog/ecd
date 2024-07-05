<?php

namespace TwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="tw_pandeigenaar")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Pandeigenaar
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
    use TimestampableTrait;
    use NotDeletableTrait;

    /**
     * @var ArrayCollection|Verhuurder[]
     *
     * @ORM\OneToMany(targetEntity="Verhuurder", mappedBy="pandeigenaar")
     */
    private $verhuurders;

    /**
     * @var PandeigenaarType
     *
     * @ORM\ManyToOne (targetEntity="TwBundle\Entity\PandeigenaarType", inversedBy="pandeigenaars", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $pandeigenaarType;

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

    public function __construct()
    {
        $this->verhuurders = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->verhuurders);
    }

    public function getVerhuurders(): ArrayCollection
    {
        return $this->verhuurders;
    }

    public function addVerhuurder(Verhuurder $verhuurder): self
    {
        if (!$this->verhuurders->contains($verhuurder)) {
            $this->verhuurders[] = $verhuurder;
            $verhuurder->setPandeigenaar($this);
        }

        return $this;
    }

    public function removeVerhuurder(Verhuurder $verhuurder): self
    {
        if ($this->verhuurders->contains($verhuurder)) {
            $this->verhuurders->removeElement($verhuurder);
            if ($verhuurder->getPandeigenaar() === $this) {
                $verhuurder->setPandeigenaar(null);
            }
        }

        return $this;
    }

    public function getPandeigenaarType(): ?PandeigenaarType
    {
        return $this->pandeigenaarType;
    }

    public function setPandeigenaarType(PandeigenaarType $pandeigenaarType): Pandeigenaar
    {
        $this->pandeigenaarType = $pandeigenaarType;

        return $this;
    }
}
