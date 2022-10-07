<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="ggw_gebieden")
 * @Gedmo\Loggable
 */
class GgwGebied
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     * @Gedmo\Versioned
     */
    private $naam;

    public function __construct($naam)
    {
        $this->naam = $naam;
    }

    public function __toString()
    {
        return $this->naam;
    }

    public function getNaam()
    {
        return $this->naam;
    }
}
