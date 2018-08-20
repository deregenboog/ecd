<?php

namespace IzBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class SuccesindicatorParticipatie extends Succesindicator
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait;

    public function __toString()
    {
        return $this->naam;
    }

    public function isDeletable()
    {
        return false;
    }
}
