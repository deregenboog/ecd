<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_groepen")
 * @ORM\HasLifecycleCallbacks
 */
class OekGroep
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $naam;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $modified;

    /**
     * @var ArrayCollection|OekKlant[]
     * @ORM\ManyToMany(targetEntity="OekKlant", inversedBy="oekGroepen")
     */
    private $oekKlanten;

    /**
     * @var ArrayCollection|OekTraining[]
     * @ORM\OneToMany(targetEntity="OekTraining", mappedBy="oekGroep")
     */
    private $oekTrainingen;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = $this->modified = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->modified = new \DateTime();
    }

    public function __construct()
    {
        $this->oekKlanten    = new ArrayCollection();
        $this->oekTrainingen = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->naam;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function getOekKlanten()
    {
        return $this->oekKlanten;
    }

    public function addOekKlant(OekKlant $oekKlant)
    {
        $this->oekKlanten->add($oekKlant);

        return $this;
    }

    public function removeOekKlant(OekKlant $oekKlant)
    {
        $this->oekKlanten->remove($oekKlant);

        return $this;
    }

    public function getOekTrainingen()
    {
        return $this->oekTrainingen;
    }

    public function addOekTraining(OekTraining $oekTraining)
    {
        $this->oekTrainingen->add($oekTraining);

        return $this;
    }

    public function removeOekTraining(OekTraining $oekTraining)
    {
        $this->oekTrainingen->remove($oekTraining);

        return $this;
    }
}
