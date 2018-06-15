<?php

namespace OdpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class HuurverzoekAfsluiting extends Afsluiting
{
    /**
     * @var Huurverzoek
     *
     * @ORM\OneToMany(targetEntity="Huurverzoek", mappedBy="afsluiting")
     */
    protected $huurverzoeken;

    public function __construct()
    {
        $this->huurverzoeken = new ArrayCollection();
    }

    public function isDeletable()
    {
        return 0 === count($this->huurverzoeken);
    }
}
