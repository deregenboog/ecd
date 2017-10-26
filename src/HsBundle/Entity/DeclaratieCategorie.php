<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_declaratie_categorieen")
 * @Gedmo\Loggable
 */
class DeclaratieCategorie
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
     * @ORM\OneToMany(targetEntity="Declaratie", mappedBy="declaratieCategorie")
     */
    private $declaraties;

    public function __construct($naam = null)
    {
        $this->naam = $naam;
        $this->declaraties = new ArrayCollection();
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

    public function getDeclaraties()
    {
        return $this->declaraties;
    }

    public function addDeclaratie(Declaratie $declaratie)
    {
        $this->declaraties[] = $declaratie;
        $declaratie->setDeclaratieCategorie($this);

        return $this;
    }

    public function isDeletable()
    {
        return 0 === count($this->declaraties);
    }
}
