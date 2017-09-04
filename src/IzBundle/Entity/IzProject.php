<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_projecten")
 * @Gedmo\Loggable
 */
class IzProject
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
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @ORM\Column(name="heeft_koppelingen", type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $heeftKoppelingen = true;

    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->naam;
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

    public function getHeeftKoppelingen()
    {
        return $this->heeftKoppelingen;
    }

    public function setHeeftKoppelingen($heeftKoppelingen)
    {
        $this->heeftKoppelingen = $heeftKoppelingen;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(\DateTime $einddatum = null)
    {
        $this->einddatum = $einddatum;

        return $this;
    }
}
