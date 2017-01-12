<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="iz_projecten")
 */
class IzProject
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
     * @Column(type="date")
     */
    private $startdatum;

    /**
     * @Column(type="date")
     */
    private $einddatum;

    /**
     * @Column(name="heeft_koppelingen", type="boolean")
     */
    private $heeftKoppelingen;

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
