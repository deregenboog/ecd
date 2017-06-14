<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\IdentifiableTrait;
use AppBundle\Entity\NamableTrait;
use AppBundle\Entity\ActivatableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_trajectsoorten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Trajectsoort
{
    use IdentifiableTrait, NamableTrait, ActivatableTrait;

    /**
     * @var ArrayCollection|Traject[]
     * @ORM\OneToMany(targetEntity="Traject", mappedBy="soort")
     */
    private $trajecten;

    public function isDeletable()
    {
        return false;
    }
}
