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
 * @ORM\Table(name="clip_viacategorieen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Viacategorie
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait;

    /**
     * @var ArrayCollection|Client[]
     *
     * @ORM\OneToMany(targetEntity="Client", mappedBy="viacategorie")
     */
    private $clienten;

    public function __construct()
    {
        $this->clienten = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->clienten);
    }
}
