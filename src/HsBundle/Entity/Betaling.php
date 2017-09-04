<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_betalingen")
 * @Gedmo\Loggable
 */
class Betaling
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $referentie;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $info;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=false)
     * @Gedmo\Versioned
     */
    private $bedrag;

    /**
     * @var Factuur
     * @ORM\ManyToOne(targetEntity="Factuur", inversedBy="betalingen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $factuur;

    public function __construct(Factuur $factuur)
    {
        $this->datum = new \DateTime('today');
        $this->factuur = $factuur;
    }

    public function __toString()
    {
        return money_format('%(#1n', $this->bedrag);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getReferentie()
    {
        return $this->referentie;
    }

    public function setReferentie($referentie)
    {
        $this->referentie = $referentie;

        return $this;
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

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    public function getBedrag()
    {
        return $this->bedrag;
    }

    public function setBedrag($bedrag)
    {
        $this->bedrag = $bedrag;

        return $this;
    }

    public function getFactuur()
    {
        return $this->factuur;
    }

    public function isDeletable()
    {
        return false;
    }
}
