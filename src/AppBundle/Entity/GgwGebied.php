<?php

namespace AppBundle\Entity;

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
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $naam;

    public function __construct($naam)
    {
        $this->naam = $naam;
    }

    public function __toString()
    {
        switch ($this->naam) {
            case 'Oost':
                return 'Noord-Oost';
            case 'West':
                return 'Noord-West';
            default:
                return $this->naam;
        }
    }

    public function getNaam()
    {
        return $this->naam;
    }
}
