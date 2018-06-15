<?php

namespace OdpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class HuurderAfsluiting extends Afsluiting
{
    /**
     * @var Huurder
     *
     * @ORM\OneToMany(targetEntity="Huurder", mappedBy="afsluiting")
     */
    protected $huurders;

    public function __construct()
    {
        $this->huurders = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->huurders);
    }
}
