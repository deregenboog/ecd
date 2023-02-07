<?php

namespace MwBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_training")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Training
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

    public function isDeletable()
    {
        return false;
    }
}
