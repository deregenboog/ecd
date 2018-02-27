<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_intervisiegroepen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Intervisiegroep
{
    use IdentifiableTrait, NameableTrait, TimestampableTrait;

    /**
     * @var ArrayCollection|IzVrijwilliger[]
     * @ORM\ManyToMany(targetEntity="IzVrijwilliger", mappedBy="intervisiegroepen")
     */
    private $izVrijwilligers;

    public function __construct()
    {
        $this->izVrijwilligers = new ArrayCollection();
    }
}
