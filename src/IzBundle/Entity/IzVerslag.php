<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_verslagen")
 */
class IzVerslag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $opmerking;

    /**
     * @var IzDeelnemer
     * @ORM\OneToOne(targetEntity="IzDeelnemer", inversedBy="izIntake")
     * @ORM\JoinColumn(name="iz_deelnemer_id")
     */
    private $izDeelnemer;

    /**
     * @var IzKoppeling
     * @ORM\OneToOne(targetEntity="IzKoppeling")
     * @ORM\JoinColumn(name="iz_koppeling_id")
     */
    private $izKoppeling;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medewerker;

    public function getId()
    {
        return $this->id;
    }

    public function getIzDeelnemer()
    {
        return $this->izDeelnemer;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function setIzDeelnemer(IzDeelnemer $izDeelnemer)
    {
        $this->izDeelnemer = $izDeelnemer;

        return $this;
    }

    public function getOpmerking()
    {
        return $this->opmerking;
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;
        return $this;
    }

    public function getIzKoppeling()
    {
        return $this->izKoppeling;
    }

    public function setIzKoppeling(IzKoppeling $izKoppeling)
    {
        $this->izKoppeling = $izKoppeling;
        return $this;
    }

}
