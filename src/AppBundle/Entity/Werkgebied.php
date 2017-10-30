<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="werkgebieden")
 * @Gedmo\Loggable
 */
class Werkgebied
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $naam;

    public function __toString()
    {
        return $this->naam;
    }

    public function getNaam()
    {
        return $this->naam;
    }
}
