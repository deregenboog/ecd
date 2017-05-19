<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="stadsdelen")
 * @Gedmo\Loggable
 */
class Stadsdeel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $postcode;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $stadsdeel;

    public function __toString()
    {
        return $this->stadsdeel;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getStadsdeel()
    {
        return $this->stadsdeel;
    }
}
