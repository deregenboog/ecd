<?php

namespace TwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_aanvullinginkomen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class AanvullingInkomen
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait, NotDeletableTrait;

    /**
     * @var Verhuurders[]
     *
     * @ORM\OneToMany(targetEntity="TwBundle\Entity\Verhuurder",mappedBy="aanvullingInkomen")
     * @ORM\JoinColumn(nullable=true)
     */
    private $verhuurders;
}
