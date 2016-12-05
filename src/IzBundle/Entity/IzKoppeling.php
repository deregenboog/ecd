<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_koppelingen")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"hulpvraag" = "IzHulpvraag", "hulpaanbod" = "IzHulpaanbod"})
 */
abstract class IzKoppeling
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
     * @ORM\Column(type="datetime")
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $einddatum;

    /**
     * @ORM\Column(name="koppeling_startdatum", type="datetime")
     */
    protected $koppelingStartdatum;

    /**
     * @ORM\Column(name="koppeling_einddatum", type="datetime")
     */
    protected $koppelingEinddatum;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $medewerker;

    /**
     * @var IzProject
     * @ORM\ManyToOne(targetEntity="IzProject")
     * @ORM\JoinColumn(name="project_id", nullable=false)
     */
    protected $izProject;

    /**
     * @var IzDeelnemer
     * @ORM\ManyToOne(targetEntity="IzDeelnemer", inversedBy="izKoppelingen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     */
    private $izDeelnemer;

    public function getId()
    {
        return $this->id;
    }

    public function getIzDeelnemer()
    {
        return $this->izDeelnemer;
    }

    public function setDeelnemer(IzDeelnemer $izDeelnemer)
    {
        $this->izKlant = $izDeelnemer;

        return $this;
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

    public function getIzProject()
    {
        return $this->izProject;
    }

    public function setIzProject(IzProject $izProject)
    {
        $this->izProject = $izProject;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getKoppelingStartdatum()
    {
        return $this->koppelingStartdatum;
    }

    public function setKoppelingStartdatum(\DateTime $koppelingStartdatum = null)
    {
        $this->koppelingStartdatum = $koppelingStartdatum;

        return $this;
    }

    public function getKoppelingEinddatum()
    {
        return $this->koppelingEinddatum;
    }

    public function setKoppelingEinddatum(\DateTime $koppelingEinddatum = null)
    {
        $this->koppelingEinddatum = $koppelingEinddatum;

        return $this;
    }
}
