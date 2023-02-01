<?php

namespace ClipBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_communicatiekanalen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Communicatiekanaal
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

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
