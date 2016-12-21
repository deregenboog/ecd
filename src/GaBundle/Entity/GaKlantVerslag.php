<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Klant;

/**
 * @ORM\Entity
 */
class GaKlantVerslag extends GaVerslag
{
    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     */
    protected $klant;
}
