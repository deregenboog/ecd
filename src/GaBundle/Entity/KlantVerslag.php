<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Klant;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class KlantVerslag extends Verslag
{
    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     * @Gedmo\Versioned
     */
    protected $klant;

    public function __construct(Klant $klant)
    {
        $this->klant = $klant;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }
}
