<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="stadsdelen")
 */
class Stadsdeel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $postcode;

    /**
     * @ORM\Column(type="string")
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
