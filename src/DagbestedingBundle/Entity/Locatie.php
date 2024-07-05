<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="dagbesteding_locaties")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Locatie
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

    /**
     * @var Project[]
     *
     * @ORM\OneToMany(targetEntity="DagbestedingBundle\Entity\Project", mappedBy="locatie", cascade={"persist","remove"} )
     */
    protected $projecten;

    public function isDeletable()
    {
        return false;
    }
}
