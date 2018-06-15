<?php

namespace OdpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class VerhuurderAfsluiting extends Afsluiting
{
    /**
     * @var Verhuurder
     *
     * @ORM\OneToMany(targetEntity="Verhuurder", mappedBy="afsluiting")
     */
    protected $verhuurders;

    public function __construct()
    {
        $this->verhuurders = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->verhuurders);
    }
}
