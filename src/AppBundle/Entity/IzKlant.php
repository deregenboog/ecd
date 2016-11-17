<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class IzKlant extends IzDeelnemer
{
    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="Klant")
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     */
    protected $klant;

    /**
     * @var ArrayCollection|IzKoppeling[]
     * @ORM\OneToMany(targetEntity="IzKoppeling", mappedBy="izKlant")
     */
    private $izHulpvragen;

    public function __construct()
    {
        $this->izHulpvragen = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->klant;
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
