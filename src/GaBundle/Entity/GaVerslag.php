<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_verslagen")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="model", type="string")
 * @ORM\DiscriminatorMap({"Klant" = "GaKlantVerslag", "Vrijwilliger" = "GaVrijwilligerVerslag"})
 */
abstract class GaVerslag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $medewerker;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $opmerking;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $modified;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }
}
