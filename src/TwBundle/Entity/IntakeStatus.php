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
 * @ORM\Table(name="tw_intakestatus")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class IntakeStatus
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait, NotDeletableTrait;
}
