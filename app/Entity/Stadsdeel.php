<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;

/**
 * @Entity
 * @Table(name="stadsdelen")
 */
class Stadsdeel
{
    /**
     * @Id
     * @Column(type="string")
     */
    private $postcode;

    /**
     * @Column(type="string")
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
