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
     * @ORM\JoinColumn(nullable=true)
     */
    private $hsKlus;

    /**
     * @var HsFactuur
     * @ManyToOne(targetEntity="HsFactuur", inversedBy="hsRegistraties")
     */
    private $hsFactuur;

    /**
     * @var HsDeclaratieCategorie
     * @ManyToOne(targetEntity="HsDeclaratieCategorie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hsDeclaratieCategorie;

    /**
     * @var Medewerker
     * @ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medewerker;

    public function __construct(HsKlus $hsKlus, Medewerker $medewerker = null)
    {
        $this->hsKlus = $hsKlus;
        $this->datum = $hsKlus->getDatum();
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

    public function getInfo()
    {
        return $this->info;
    }

    public function getBedrag()
    {
        return $this->bedrag;
    }

    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    public function setBedrag($bedrag)
    {
        $this->bedrag = $bedrag;

        return $this;
    }

    public function getHsDeclaratieCategorie()
    {
        return $this->hsDeclaratieCategorie;
    }

    public function setHsDeclaratieCategorie(HsDeclaratieCategorie $hsDeclaratieCategorie)
    {
        $this->hsDeclaratieCategorie = $hsDeclaratieCategorie;

        return $this;
    }
}
