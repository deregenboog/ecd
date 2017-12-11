<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\IdentifiableTrait;
use AppBundle\Entity\NamableTrait;
use AppBundle\Entity\ActivatableTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_resultaatgebiedsoorten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Resultaatgebiedsoort
{
    use IdentifiableTrait, NamableTrait, ActivatableTrait;

    /**
     * @var ArrayCollection|Resultaatgebied[]
     * @ORM\OneToMany(targetEntity="Resultaatgebied", mappedBy="soort")
     */
    private $resultaatgebieden;

    public function __construct()
    {
        $this->resultaatgebieden = new ArrayCollection();
    }

    public function isDeletable()
    {
        return false;
    }
}
