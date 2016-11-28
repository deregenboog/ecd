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
 * @Table(name="hs_declaraties")
 */
class HsDeclaratie
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
     * @var Medewerker
     * @ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medewerker;

    public function __construct(HsKlus $hsKlus)
    {
        $this->datum = $hsKlus->getDatum();
        $this->hsKlus = $hsKlus;
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

    public function getHsKlus()
    {
        return $this->hsKlus;
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

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function getBedrag()
    {
        return $this->bedrag;
    }
}
