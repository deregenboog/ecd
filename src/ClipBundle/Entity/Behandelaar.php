<?php

namespace ClipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\RequiredMedewerkerTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\IdentifiableTrait;
use AppBundle\Entity\ActivatableTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="clip_behandelaars")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Behandelaar
{
    use IdentifiableTrait, RequiredMedewerkerTrait, ActivatableTrait;

    /**
     * @var ArrayCollection|Vraag[]
     *
     * @ORM\OneToMany(targetEntity="Vraag", mappedBy="hulpvrager")
     */
    private $vragen;

    public function __construct()
    {
        $this->vragen = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->medewerker;
    }

    public function isDeletable()
    {
        return 0 === count($this->vragen);
    }
}
