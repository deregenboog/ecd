<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_trajectsoorten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Trajectsoort
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

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
