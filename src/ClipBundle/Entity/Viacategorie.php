<?php

namespace ClipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NamableTrait;
use AppBundle\Model\ActivatableTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_viacategorieen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Viacategorie
{
    use IdentifiableTrait, NamableTrait, ActivatableTrait;

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
