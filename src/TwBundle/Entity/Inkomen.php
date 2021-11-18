<?php

namespace TwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\OrderableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_inkomen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Inkomen
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait, NotDeletableTrait, OrderableTrait;
}
