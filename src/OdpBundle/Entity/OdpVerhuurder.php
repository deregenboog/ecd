<?php

namespace OdpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="OdpBundle\Repository\OdpDeelnemerRepository")
 * @ORM\HasLifecycleCallbacks
 */
class OdpVerhuurder extends OdpDeelnemer
{
    /**
     * @var ArrayCollection|OdpHuuraanbod[]
     * @ORM\OneToMany(targetEntity="OdpHuuraanbod", mappedBy="odpVerhuurder")
     */
    private $odpHuuraanbiedingen;

    public function isDeletable()
    {
        return count($this->odpHuuraanbiedingen) === 0;
    }

    public function getHuuraanbiedingen()
    {
        return $this->odpHuuraanbiedingen;
    }
}
