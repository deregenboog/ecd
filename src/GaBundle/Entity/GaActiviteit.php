<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten")
 */
class GaActiviteit
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var GaGroep
     *
     * @ORM\ManyToOne(targetEntity="GaGroep", inversedBy="gaActiviteiten")
     * @ORM\JoinColumn(name="groepsactiviteiten_groep_id", nullable=false)
     */
    private $gaGroep;

    /**
     * @ORM\Column(nullable=true)
     */
    private $naam;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datum;

    /**
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    private $tijd;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $afgesloten;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @var ArrayCollection|GaKlantDeelname[]
     *
     * @ORM\OneToMany(targetEntity="GaKlantDeelname", mappedBy="gaActiviteit")
     */
    private $gaKlantDeelnames;

    /**
     * @var ArrayCollection|GaVrijwilligerDeelname[]
     *
     * @ORM\OneToMany(targetEntity="GaVrijwilligerDeelname", mappedBy="gaActiviteit")
     */
    private $gaVrijwilligerDeelnames;

    public function __construct()
    {
        $this->gaKlantDeelnames = new ArrayCollection();
        $this->gaVrijwilligerDeelnames = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
}
