<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_facturen")
 * @Gedmo\Loggable
 */
class Factuur
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $nummer;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Gedmo\Versioned
     */
    private $betreft;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Gedmo\Versioned
     */
    private $bedrag;

    /**
     * @var Klant
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="facturen")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var ArrayCollection|Klus[]
     * @ORM\ManyToMany(targetEntity="Klus", inversedBy="facturen")
     */
    private $klussen;

    /**
     * @var ArrayCollection|Registratie[]
     * @ORM\OneToMany(targetEntity="Registratie", mappedBy="factuur")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $registraties;

    /**
     * @var ArrayCollection|Declaratie[]
     * @ORM\OneToMany(targetEntity="Declaratie", mappedBy="factuur")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $declaraties;

    /**
     * @var ArrayCollection|Betaling[]
     * @ORM\OneToMany(targetEntity="Betaling", mappedBy="factuur", cascade={"persist"})
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $betalingen;

    /**
     * @var ArrayCollection|Herinnering[]
     * @ORM\OneToMany(targetEntity="Herinnering", mappedBy="factuur", cascade={"persist"})
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $herinneringen;

    public function __construct(Klant $klant, $nummer, $betreft)
    {
        $this->klant = $klant;
        $this->nummer = $nummer;
        $this->betreft = $betreft;

        $this->betalingen = new ArrayCollection();
        $this->declaraties = new ArrayCollection();
        $this->klussen = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->herinneringen = new ArrayCollection();

        $this->setDatum(new \DateTime());
    }

    public function __toString()
    {
        return $this->nummer;
    }

    public function isEmpty()
    {
        return 0 === count($this->declaraties)
            && 0 === count($this->registraties)
            && 0 === count($this->betalingen)
            && 0 === count($this->klussen)
            && 0 === count($this->herinneringen)
        ;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNummer()
    {
        return $this->nummer;
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

    public function getKlussen()
    {
        return $this->klussen;
    }

    public function addKlus(Klus $klus)
    {
        if (!$this->klussen->contains($klus)) {
            $this->klussen[] = $klus;
        }

        return $this;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getRegistraties()
    {
        return $this->registraties;
    }

    public function addRegistratie(Registratie $registratie)
    {
        $this->registraties[] = $registratie;
        $registratie->setFactuur($this);

        if (!$this->klussen->contains($registratie->getKlus())) {
            $this->klussen->add($registratie->getKlus());
        }

        return $this;
    }

    public function getDeclaraties()
    {
        return $this->declaraties;
    }

    public function addDeclaratie(Declaratie $declaratie)
    {
        $this->declaraties[] = $declaratie;
        $declaratie->setFactuur($this);

        if (!$this->klussen->contains($declaratie->getKlus())) {
            $this->klussen->add($declaratie->getKlus());
        }

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

    public function isDeletable()
    {
        return false;
    }

    public function getBetalingen()
    {
        return $this->betalingen;
    }

    public function addBetaling(Betaling $betaling)
    {
        $this->betalingen[] = $betaling;
        $betaling->setFactuur($this);

        return $this;
    }

    public function getBetaald()
    {
        $betaald = 0.0;
        foreach ($this->betalingen as $betaling) {
            $betaald += $betaling->getBedrag();
        }

        return $betaald;
    }

    public function getSaldo()
    {
        return $this->bedrag - $this->getBetaald();
    }

    public function getBetreft()
    {
        return $this->betreft;
    }

    public function getHerinneringen()
    {
        return $this->herinneringen;
    }

    public function addHerinnering(Herinnering $herinnering)
    {
        $this->herinneringen[] = $herinnering;
        $herinnering->setFactuur($this);

        return $this;
    }
}
