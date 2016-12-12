<?php

namespace HsBundle\Entity;

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
class HsRegistratie
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
     * @var HsKlus
     * @ManyToOne(targetEntity="HsKlus", inversedBy="hsRegistraties")
     */
    private $hsKlus;

    /**
     * @var HsFactuur
     * @ManyToOne(targetEntity="HsFactuur", inversedBy="hsRegistraties")
     * @ORM\JoinColumn(nullable=true)
     */
    private $hsFactuur;

    /**
     * @var HsVrijwilliger
     * @ManyToOne(targetEntity="HsVrijwilliger", inversedBy="hsRegistraties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hsVrijwilliger;

    /**
     * @var HsActiviteit
     * @ORM\ManyToOne(targetEntity="HsActiviteit", inversedBy="hsKlussen")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hsActiviteit;

    /**
     * @var Medewerker
     * @ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medewerker;

    public function __construct(HsKlus $hsKlus, HsVrijwilliger $hsVrijwilliger = null, Medewerker $medewerker = null)
    {
        $this->datum = $hsKlus->getDatum();
        $this->hsKlus = $hsKlus;
        $this->hsActiviteit = $hsKlus->getHsActiviteit();
        $this->hsVrijwilliger = $hsVrijwilliger;
        $this->medewerker = $medewerker;
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

    public function getHsKlus()
    {
        return $this->hsKlus;
    }

    public function getHsVrijwilliger()
    {
        return $this->hsVrijwilliger;
    }

    public function setHsVrijwilliger(HsVrijwilliger $hsVrijwilliger)
    {
        $this->hsVrijwilliger = $hsVrijwilliger;

        return $this;
    }

    public function getHsFactuur()
    {
        return $this->hsFactuur;
    }

    public function setHsFactuur(HsFactuur $hsFactuur)
    {
        $this->hsFactuur = $hsFactuur;

        return $this;
    }

    public function getHsActiviteit()
    {
        return $this->hsActiviteit;
    }

    public function setHsActiviteit(HsActiviteit $hsActiviteit)
    {
        $this->hsActiviteit = $hsActiviteit;

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
