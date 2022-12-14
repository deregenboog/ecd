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
 * @ORM\Table(name="tw_pandeigenaartype")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class PandeigenaarType
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait, NotDeletableTrait;


    /**
     * @var ArrayCollection|Pandeigenaar[]
     *
     * @ORM\OneToMany(targetEntity="TwBundle\Entity\Pandeigenaar", mappedBy="pandeigenaarType")
     */
    private $pandeigenaars;

    public function __construct()
    {
        $this->pandeigenaars = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === (is_array($this->verhuurders) || $this->verhuurders instanceof \Countable ? count($this->verhuurders) : 0);
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
