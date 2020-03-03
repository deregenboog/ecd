<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityNotFoundException;
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

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $zichtbaar;

    public function __construct($naam, $zichtbaar = 1)
    {
        $this->naam = $naam;
        $this->zichtbaar = $zichtbaar;
    }

    public function __toString()
    {
        try {
            return $this->naam;
        }
        catch(EntityNotFoundException $e)
        {
            return "";
        }
        catch(Exception $e)
        {
            return "";
        }

    }

    public function getNaam()
    {
        return $this->naam;
    }

    /**
     * @return mixed
     */
    public function getZichtbaar()
    {
        return $this->zichtbaar;
    }

    /**
     * @param mixed $zichtbaar
     */
    public function setZichtbaar($zichtbaar): void
    {
        $this->zichtbaar = $zichtbaar;
    }
}
