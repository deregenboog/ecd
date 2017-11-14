<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\IdentifiableTrait;
use AppBundle\Entity\NamableTrait;
use AppBundle\Entity\ActivatableTrait;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_matching_klanten")
 * @Gedmo\Loggable
 */
class MatchingKlant
{
    use IdentifiableTrait;

    /**
     * @var IzKlant
     *
     * @ORM\OneToOne(targetEntity="IzKlant", inversedBy="matching")
     * @ORM\JoinColumn(name="iz_klant_id")
     */
    private $izKlant;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $info;

    /**
     * @var Collection|Doelgroep[]
     *
     * @ORM\ManyToMany(targetEntity="Doelgroep")
     */
    private $doelgroepen;

    /**
     * @var Hulpvraagsoort
     *
     * @ORM\ManyToOne(targetEntity="Hulpvraagsoort")
     */
    private $hulpvraagsoort;

    /**
     * @var bool
     *
     * @ORM\Column(name="spreekt_nederlands", type="boolean")
     */
    private $spreektNederlands;

    public function __construct(IzKlant $izKlant)
    {
        $this->izKlant = $izKlant;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function getDoelgroepen()
    {
        return $this->doelgroepen;
    }

    public function getHulpvraagsoort()
    {
        return $this->hulpvraagsoort;
    }

    public function isSpreektNederlands()
    {
        return $this->spreektNederlands;
    }

    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    public function setDoelgroepen($doelgroepen)
    {
        $this->doelgroepen = $doelgroepen;

        return $this;
    }

    public function setHulpvraagsoort(Hulpvraagsoort $hulpvraagsoort)
    {
        $this->hulpvraagsoort = $hulpvraagsoort;

        return $this;
    }

    public function setSpreektNederlands($spreektNederlands)
    {
        $this->spreektNederlands = (bool) $spreektNederlands;

        return $this;
    }
}
