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
 * @ORM\Table(name="tw_pandeigenaar")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Pandeigenaar
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait, NotDeletableTrait;


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
     * @ORM\JoinColumn(nullable=false)
     */
    private $pandeigenaarType;

    public function __construct()
    {
        $this->verhuurders = new ArrayCollection();
    }



    public function isDeletable()
    {
        return 0 === count($this->verhuurders);
    }

    /**
     * @return PandeigenaarType
     */
    public function getPandeigenaarType(): ?PandeigenaarType
    {
        return $this->pandeigenaarType;
    }

    /**
     * @param PandeigenaarType $pandeigenaarType
     * @return Pandeigenaar
     */
    public function setPandeigenaarType(PandeigenaarType $pandeigenaarType): Pandeigenaar
    {
        $this->pandeigenaarType = $pandeigenaarType;
        return $this;
    }



}
