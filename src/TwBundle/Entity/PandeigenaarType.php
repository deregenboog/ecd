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
 * @ORM\Table(name="tw_pandeigenaartype")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class PandeigenaarType
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;
    use TimestampableTrait;
    use NotDeletableTrait;

    /**
     * @var bool
     *
     * @ORM\Column(name="`active`", type="boolean", options={"default":1})
     *
     * @Gedmo\Versioned
     */
    protected $actief = true;

    /**
     * @var ArrayCollection|Pandeigenaar[]
     *
     * @ORM\OneToMany(targetEntity="TwBundle\Entity\Pandeigenaar", mappedBy="pandeigenaarType")
     */
    private $pandeigenaars;

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
        $this->pandeigenaars = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->pandeigenaars);
    }

    public function getPandeigenaars(): ArrayCollection
    {
        return $this->pandeigenaars;
    }

    public function addPandeigenaar(Pandeigenaar $pandeigenaar): self
    {
        if (!$this->pandeigenaars->contains($pandeigenaar)) {
            $this->pandeigenaars[] = $pandeigenaar;
            $pandeigenaar->setPandeigenaarType($this);
        }

        return $this;
    }
}
