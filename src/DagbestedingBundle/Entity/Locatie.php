<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\IdentifiableTrait;
use AppBundle\Entity\NamableTrait;
use AppBundle\Entity\ActivatableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_locaties")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Locatie
{
    use IdentifiableTrait, NamableTrait, ActivatableTrait;

    public function isDeletable()
    {
        return false;
    }
}
