<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="hs_activiteiten")
 */
class HsActiviteit
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $naam;

    /**
     * @Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @Column(type="datetime", nullable=false)
     */
    private $modified;

    /**
     * @OneToMany(targetEntity="HsKlus", mappedBy="hsActiviteit")
     */
    private $hsKlussen;

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

    public function getHsKlussen()
    {
        return $this->hsKlussen;
    }

    public function isDeletable()
    {
        return count($this->hsKlussen) === 0;
    }
}
