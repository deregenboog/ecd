<?php

namespace Tests\HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use AppBundle\Entity\Medewerker;

/**
 * @Entity
 * @Table(name="hs_registraties")
 */
class Registratie
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     * @Column(type="date")
     */
    private $datum;

    /**
     * @var \DateTime
     * @Column(type="time")
     */
    private $start;

    /**
     * @var \DateTime
     * @Column(type="time")
     */
    private $eind;

    /**
     * @var float
     * @Column(type="float", nullable=true)
     */
    private $reiskosten;

    /**
     * @var Klus
     * @ManyToOne(targetEntity="Klus", inversedBy="registraties")
     */
    private $klus;

    /**
     * @var Factuur
     * @ManyToOne(targetEntity="Factuur", inversedBy="registraties")
     * @ORM\JoinColumn(nullable=true)
     */
    private $factuur;

    /**
     * @var Vrijwilliger
     * @ManyToOne(targetEntity="Vrijwilliger", inversedBy="registraties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vrijwilliger;

    /**
     * @var Activiteit
     * @ORM\ManyToOne(targetEntity="Activiteit", inversedBy="klussen")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activiteit;

    /**
     * @var Medewerker
     * @ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medewerker;

    public function __construct(Klus $klus, Vrijwilliger $vrijwilliger = null)
    {
        $this->datum = $klus->getDatum();
        $this->klus = $klus;
        $this->activiteit = $klus->getActiviteit();
        $this->vrijwilliger = $vrijwilliger;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setStart(\DateTime $start)
    {
        $this->start = $start;

        return $this;
    }

    public function getEind()
    {
        return $this->eind;
    }

    public function setEind(\DateTime $eind)
    {
        $this->eind = $eind;

        return $this;
    }

    public function getReiskosten()
    {
        return $this->reiskosten;
    }

    public function setReiskosten($reiskosten)
    {
        $this->reiskosten = $reiskosten;

        return $this;
    }

    public function getKlus()
    {
        return $this->klus;
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    public function setVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }

    public function getFactuur()
    {
        return $this->factuur;
    }

    public function setFactuur(Factuur $factuur)
    {
        $this->factuur = $factuur;

        return $this;
    }

    public function getActiviteit()
    {
        return $this->activiteit;
    }

    public function setActiviteit(Activiteit $activiteit)
    {
        $this->activiteit = $activiteit;

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

    public function getUren()
    {
        $seconds = $this->eind->getTimestamp() - $this->start->getTimestamp();

        return $seconds / 3600;
    }

    public function getDagdelen()
    {
        return $this->getUren() > 3 ? 2 : 1;
    }
}
