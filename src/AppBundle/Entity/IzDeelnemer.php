<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var IzAfsluiting
     * @ORM\ManyToOne(targetEntity="IzAfsluiting")
     * @ORM\JoinColumn(name="iz_afsluiting_id")
     */
    protected $izAfsluiting;

    public function getId()
    {
        return $this->id;
    }

    public function getIzAfsluiting()
    {
        return $this->izAfsluiting;
    }
}
