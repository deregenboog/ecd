<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Entity
 * @Table(name="hs_declaratie_categorieen")
 * @Gedmo\Loggable
 */
class DeclaratieCategorie
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @Column(type="datetime", nullable=false)
     * @Gedmo\Versioned
     */
    private $created;

    /**
     * @Column(type="datetime", nullable=false)
     * @Gedmo\Versioned
     */
    private $modified;

    /**
     * @OneToMany(targetEntity="Declaratie", mappedBy="declaratieCategorie")
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
        return count($this->declaraties) === 0;
    }
}
