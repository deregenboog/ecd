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
 *
 * @ORM\Table(name="dagbesteding_resultaatgebiedsoorten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Resultaatgebiedsoort
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

    /**
     * @var ArrayCollection|Resultaatgebied[]
     *
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
