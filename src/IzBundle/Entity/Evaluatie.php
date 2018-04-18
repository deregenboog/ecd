<?php

namespace IzBundle\Entity;

use AppBundle\Model\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Evaluatie extends Verslag
{
    use NameableTrait;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $eenzaamheidscijfer;

    public function __construct($naam = null, \DateTime $datum = null)
    {
        $this->naam = $naam;
        $this->datum = $datum;
    }

    public function getEenzaamheidscijfer()
    {
        return $this->eenzaamheidscijfer;
    }

    public function setEenzaamheidscijfer($eenzaamheidscijfer = null)
    {
        $this->eenzaamheidscijfer = $eenzaamheidscijfer;

        return $this;
    }
}
