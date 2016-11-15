<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;

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
     * @var string
     * @Column(type="string")
     */
    private $code;

    /**
     * @var string
     * @Column(type="text")
     */
    private $info;

    /**
     * @var float
     * @Column(type="float")
     */
    private $bedrag;

    /**
     * @var HsKlus
     * @ManyToOne(targetEntity="HsKlus", inversedBy="hsRegistraties")
     */
    private $hsKlus;

    /**
     * @var HsFactuur
     * @ManyToOne(targetEntity="HsFactuur", inversedBy="hsRegistraties")
     */
    private $hsFactuur;

    /**
     * @var HsVrijwilliger
     * @ManyToOne(targetEntity="HsVrijwilliger", inversedBy="hsRegistraties")
     */
    private $hsVrijwilliger;

    public function __construct(HsKlus $hsKlus, HsVrijwilliger $hsVrijwilliger)
    {
        $this->datum = $hsKlus->getDatum();
        $this->hsKlus = $hsKlus;
        $this->hsVrijwilliger = $hsVrijwilliger;
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
