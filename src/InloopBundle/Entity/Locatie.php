<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="locaties")
 */
class Locatie
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(nullable=false)
     */
    private $naam;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $nachtopvang = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $gebruikersruimte = false;

    /**
     * @ORM\Column(name="maatschappelijkwerk", type="boolean", nullable=true)
     */
    private $maatschappelijkWerk = false;

    /**
     * @ORM\Column(name="datum_van", type="date", nullable=false)
     */
    private $datumVan;

    /**
     * @ORM\Column(name="datum_tot", type="date", nullable=false)
     */
    private $datumTot;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    public function __toString()
    {
        return $this->naam;
    }

    public function getId()
    {
        return $this->id;
    }
}
