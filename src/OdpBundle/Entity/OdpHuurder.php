<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;

/**
 * @ORM\Entity(repositoryClass="OdpBundle\Repository\OdpDeelnemerRepository")
 * @ORM\HasLifecycleCallbacks
 */
class OdpHuurder extends OdpDeelnemer
{
    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     */
    protected $klant;

    /**
     * @var ArrayCollection|OdpHuurverzoek[]
     * @ORM\OneToMany(targetEntity="OdpHuurverzoek", mappedBy="odpHuurder")
     */
    private $odpHuurverzoeken;

    public function isDeletable()
    {
        return count($this->odpHuurverzoeken) === 0;
    }

    public function getHuurverzoeken() {
        return $this->odpHuurverzoeken;
    }
}
