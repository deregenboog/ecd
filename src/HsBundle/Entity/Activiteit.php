<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_activiteiten")
 * @Gedmo\Loggable
 */
class Activiteit
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Versioned
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Versioned
     */
    private $modified;

    /**
     * @ORM\OneToMany(targetEntity="Klus", mappedBy="activiteit")
     */
    private $klussen;

    public function __construct()
    {
        $this->klussen = new ArrayCollection();
        $this->created = $this->modified = new \DateTime();
    }

    public function __toString()
    {
        return $this->naam;
    }

    public function getId()
    {
        return $this->id;
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

    public function getKlussen()
    {
        return $this->klussen;
    }

    public function isDeletable()
    {
        return count($this->klussen) === 0;
    }
}
