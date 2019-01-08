<?php

namespace ErOpUitBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="eropuit_uitschrijfredenen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Uitschrijfreden
{
    use IdentifiableTrait, NameableTrait, TimestampableTrait;
}
