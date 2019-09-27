<?php

namespace IzBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_ontstaan_contacten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class ContactOntstaan
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait;
}
