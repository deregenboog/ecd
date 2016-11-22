<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_koppelingen")
 */
class IzKoppeling
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
    protected $einddatum;

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

    public function getId()
    {
        return $this->id;
    }

    public function getIzKlant()
    {
        return $this->izKlant;
    }

    public function setKlant(IzKlant $izKlant)
    {
        $this->izKlant = $izKlant;

        return $this;
    }

    public function getIzVrijwilliger()
    {
        return $this->izVrijwilliger;
    }

    public function setVrijwilliger(IzVrijwilliger $izVrijwilliger)
    {
        $this->izVrijwilliger = $izVrijwilliger;

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
}
