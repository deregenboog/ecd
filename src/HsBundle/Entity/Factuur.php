<?php

namespace HsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use HsBundle\Exception\InvoiceLockedException;
use HsBundle\Exception\InvoiceNotLockedException;

/**
 * @ORM\Entity(repositoryClass="HsBundle\Repository\FactuurRepository")
 * @ORM\Table(name="hs_facturen")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({"Factuur" = "Factuur", "Creditfactuur" = "Creditfactuur"})
 * @Gedmo\Loggable
 */
class Factuur
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $nummer;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    protected $datum;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Gedmo\Versioned
     */
    protected $betreft;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Gedmo\Versioned
     */
    protected $bedrag;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $locked = false;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    protected $oninbaar = false;

    /**
     * @var Klant
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="facturen")
     * @Gedmo\Versioned
     */
    protected $klant;

    /**
     * @var ArrayCollection|Registratie[]
     * @ORM\OneToMany(targetEntity="Registratie", mappedBy="factuur", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $registraties;

    /**
     * @var ArrayCollection|Declaratie[]
     * @ORM\OneToMany(targetEntity="Declaratie", mappedBy="factuur", cascade={"persist"})
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

    public function __construct(Klant $klant, $nummer = null, $betreft = null)
    {
        $klant->addFactuur($this);

        $this->nummer = $nummer;
        $this->betreft = $betreft;

        $this->betalingen = new ArrayCollection();
        $this->declaraties = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->herinneringen = new ArrayCollection();

        $this->datum = new \DateTime('last day of this month');
    }

    public function __toString()
    {
        return $this->nummer;
    }

    public function isDeletable()
    {
        return $this->isEmpty();
    }

    public function isEmpty()
    {
        return 0.0 === $this->bedrag
            && 0 === count($this->declaraties)
            && 0 === count($this->registraties)
            && 0 === count($this->betalingen)
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

    public function getKlussen()
    {
        $klussen = new ArrayCollection();

        foreach ($this->declaraties as $declaratie) {
            if (!$klussen->contains($declaratie->getKlus())) {
                $klussen->add($declaratie->getKlus());
            }
        }

        foreach ($this->registraties as $registratie) {
            if (!$klussen->contains($registratie->getKlus())) {
                $klussen->add($registratie->getKlus());
            }
        }

        return $klussen;
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
        if ($this->isLocked()) {
            throw new InvoiceLockedException();
        }

        if ($registratie->getFactuur()) {
            $registratie->getFactuur()->removeRegistratie($registratie);
        }

        $this->registraties[] = $registratie;
        $registratie->setFactuur($this);

        $this->updateDatum();
        $this->calculateBedrag();

        return $this;
    }

    public function removeRegistratie(Registratie $registratie)
    {
        if ($this->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->registraties->removeElement($registratie);
        $registratie->setFactuur(null);

        $this->updateDatum();
        $this->calculateBedrag();

        return $this;
    }

    public function getDeclaraties()
    {
        return $this->declaraties;
    }

    public function addDeclaratie(Declaratie $declaratie)
    {
        if ($this->isLocked()) {
            throw new InvoiceLockedException();
        }

        if ($declaratie->getFactuur()) {
            $declaratie->getFactuur()->removeDeclaratie($declaratie);
        }

        $this->declaraties[] = $declaratie;
        $declaratie->setFactuur($this);

        $this->updateDatum();
        $this->calculateBedrag();

        return $this;
    }

    public function removeDeclaratie(Declaratie $declaratie)
    {
        if ($this->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->declaraties->removeElement($declaratie);
        $declaratie->setFactuur(null);

        $this->updateDatum();
        $this->calculateBedrag();

        return $this;
    }

    public function getBedrag()
    {
        return (float) $this->bedrag;
    }

    public function setBedrag($bedrag)
    {
        $this->bedrag = (float) $bedrag;

        return $this;
    }

    public function getBetalingen()
    {
        return $this->betalingen;
    }

    public function addBetaling(Betaling $betaling)
    {
        if (!$this->isLocked()) {
            throw new InvoiceNotLockedException();
        }

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

        return (float) $betaald;
    }

    public function getSaldo(): float
    {
        return round($this->bedrag - $this->getBetaald(), 2);
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
        if (!$this->isLocked()) {
            throw new InvoiceNotLockedException();
        }

        $this->herinneringen[] = $herinnering;
        $herinnering->setFactuur($this);

        return $this;
    }

    public function isLocked()
    {
        return $this->locked;
    }

    public function lock()
    {
        $this->locked = true;

        return $this;
    }

    public function isOninbaar(): bool
    {
        return $this->oninbaar;
    }

    public function setOninbaar(bool $oninbaar)
    {
        $this->oninbaar = $oninbaar;

        return $this;
    }

    public function calculateBedrag()
    {
        $bedrag = 0.0;

        foreach ($this->declaraties as $declaratie) {
            $bedrag += $declaratie->getBedrag();
        }

        foreach ($this->registraties as $registratie) {
            $bedrag += 2.5 * $registratie->getUren();
        }

        $this->bedrag = $bedrag;

        return $this;
    }

    /**
     * Sets the date to the last day of the month of the most recent declaration/registration.
     * #824
     * Krijg vragen hierover; is ongewenst. Alleen de factuurdatum moet op laatste dag van de maand; niet de interne factuurregels (#824, ingetreden sinds #641)
     * Volgens mij kan dit ook anders door dit alleen te doen wanneer de factuur definitief gemaakt wordt. En in concept dan gewoon een tekstveld maken waarin dat gezegd wordt.
     *
     * @TODO Anders implementeren zie boven.
     */
    private function updateDatum()
    {
        if (0 === count($this->declaraties) && 0 === count($this->registraties)) {
            return;
        }

        $datum = new \DateTime();
        foreach ($this->declaraties as $declaratie) {
            if (!$datum || $declaratie->getDatum() > $datum) {
                $datum->setDate(
                    $declaratie->getDatum()->format("Y"),
                    $declaratie->getDatum()->format("m"),
                    $declaratie->getDatum()->format("d")
                );
            }
        }
        foreach ($this->registraties as $registratie) {
            if (!$datum || $registratie->getDatum() > $datum) {
                $datum->setDate(
                    $registratie->getDatum()->format("Y"),
                    $registratie->getDatum()->format("m"),
                    $registratie->getDatum()->format("d")
                );
            }
        }

        $yearMonth = $datum->format('Y-m');
        while ($yearMonth == $datum->format('Y-m')) {
            $datum->modify('+1 day');
        }
        $datum->modify('-1 day');

        $this->datum = $datum;
    }
}
