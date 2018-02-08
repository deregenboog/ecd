<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\ActivatableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="inloop_binnen_via")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class BinnenVia
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait, TimestampableTrait;

    public function isDeletable()
    {
        return false;
    }
}
