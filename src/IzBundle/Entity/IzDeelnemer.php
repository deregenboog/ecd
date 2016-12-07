<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_deelnemers")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({"Klant" = "IzKlant", "Vrijwilliger" = "IzVrijwilliger"})
 */
abstract class IzDeelnemer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $modified;

    /**
     * @var ArrayCollection|IzKoppeling[]
     * @ORM\OneToMany(targetEntity="IzKoppeling", mappedBy="izDeelnemer")
     */
    private $izKoppelingen;

    /**
     * @var IzIntake
     * @ORM\OneToOne(targetEntity="IzIntake", mappedBy="izDeelnemer")
     */
    protected $izIntake;

    /**
     * @ORM\Column(name="datumafsluiting", type="date")
     */
    protected $afsluitDatum;

    /**
     * @var IzAfsluiting
     * @ORM\ManyToOne(targetEntity="IzAfsluiting")
     * @ORM\JoinColumn(name="iz_afsluiting_id")
     */
    protected $izAfsluiting;

    public function __construct()
    {
        $this->izKoppelingen = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIzAfsluiting()
    {
        return $this->izAfsluiting;
    }

    public function getIzIntake()
    {
        return $this->izIntake;
    }

    public function getAfsluitDatum()
    {
        return $this->afsluitDatum;
    }
}
