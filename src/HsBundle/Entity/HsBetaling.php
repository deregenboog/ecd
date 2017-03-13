<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_betalingen")
 */
class HsBetaling
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $referentie;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $datum;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=false)
     */
    private $bedrag;

    /**
     * @var HsFactuur
     * @ORM\ManyToOne(targetEntity="HsFactuur", inversedBy="hsFacturen")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hsFactuur;

    public function __construct(HsFactuur $hsFactur)
    {
        $this->datum = new \DateTime('today');
        $this->hsFactuur = $hsFactur;
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

    public function getHsFactuur()
    {
        return $this->hsFactuur;
    }

    public function isDeletable()
    {
        return false;
    }
}
