<?php

namespace ClipBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_training")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Training
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait;

    public function isDeletable()
    {
        return false;
    }
}
