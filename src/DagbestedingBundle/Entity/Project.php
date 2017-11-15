<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NamableTrait;
use AppBundle\Model\ActivatableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_projecten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Project
{
    use IdentifiableTrait, NamableTrait, ActivatableTrait;

    public function isDeletable()
    {
        return false;
    }
}
