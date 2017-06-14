<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait NamableTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Gedmo\Versioned
     */
    protected $naam;

    public function __toString()
    {
        return $this->getNaam();
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }
}
