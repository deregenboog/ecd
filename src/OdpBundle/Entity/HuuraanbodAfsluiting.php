<?php

namespace OdpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class HuuraanbodAfsluiting extends Afsluiting
{
    /**
     * @var Huuraanbod
     *
     * @ORM\OneToMany(targetEntity="Huuraanbod", mappedBy="afsluiting")
     */
    protected $huuraanbiedingen;

    public function __construct()
    {
        $this->huuraanbiedingen = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->huuraanbiedingen);
    }
}
