<?php

namespace ClipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\IdentifiableTrait;
use AppBundle\Entity\NamableTrait;
use AppBundle\Entity\ActivatableTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_communicatiekanalen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Communicatiekanaal
{
    use IdentifiableTrait, NamableTrait, ActivatableTrait;

    /**
     * @var ArrayCollection|Vraag[]
     *
     * @ORM\OneToMany(targetEntity="Vraag", mappedBy="communicatiekanaal")
     */
    private $vragen;

    public function __construct()
    {
        $this->vragen = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->vragen);
    }
}
