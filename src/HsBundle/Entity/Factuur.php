<?php

namespace HsBundle\Entity;

use AppBundle\Form\Model\AppDateRangeModel;
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
     * @ORM\Column(type="decimal", scale=2, nullable=true)
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

    public function __construct(Klant $klant, $nummer = null, $betreft = null, AppDateRangeModel $dateRange = null)
    {
        $klant->addFactuur($this);

        $this->nummer = $nummer;
        $this->betreft = $betreft;

        $this->betalingen = new ArrayCollection();
        $this->declaraties = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->herinneringen = new ArrayCollection();

        /**
         * When an invoice is made during registraties, see if there is a daterange available and if so, set invoice to last date of that month instead of the current month.
         *
         */
        if(null !== $dateRange)
        {
            $this->datum = new \DateTime('last day of '.$dateRange->getEnd()->format("M Y") );
        }
        else
        {
            $this->datum = new \DateTime('last day of this month');
        }


    }

    public function __toString(): string
    {
        return (string) $this->nummer;
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

        $this->registraties->add($registratie);
        $this->updateDatum();
        $this->calculateBedrag();

        $registratie->setFactuur($this);

        return $this;
    }

    public function removeRegistratie(Registratie $registratie)
    {
        if ($this->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->registraties->removeElement($registratie);


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

        $this->declaraties->add($declaratie);


        $this->updateDatum();
        $this->calculateBedrag();

        $declaratie->setFactuur($this);
        return $this;
    }

    public function removeDeclaratie(Declaratie $declaratie)
    {
        if ($this->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->declaraties->removeElement($declaratie);
       // $declaratie->setFactuur(null);

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
     *
     */
    private function updateDatum()
    {
        if (0 === count($this->declaraties) && 0 === count($this->registraties)) {
            return;
        }

        //#873 dit werd op 'vandaag' gezet. Dus bij registratie in het verleden leverde dat soms (andere maand)
        // problemen op. Want huidig > registratie. En dan kon hij geen facturen vinden binnen de daterange en
        // maakte telkens een nieuwe aan met foute factuurdatum. Daarom een datum in het verleden.
        $datum = new \DateTime("1970-01-01");


        foreach ($this->declaraties as $declaratie) {
            if ($declaratie->getDatum() > $datum) {
                //190820 even laten staan voor als blijkt dat ik toch te kort doorde bocht ben gegaan.
//                $datum->setDate(
//                    $declaratie->getDatum()->format("Y"),
//                    $declaratie->getDatum()->format("m"),
//                    $declaratie->getDatum()->format("d")
//                );

                //update datum to most advanced datum.
                $datum = $declaratie->getDatum();
            }
        }
        foreach ($this->registraties as $registratie) {
            if ($registratie->getDatum() > $datum) {
//                $datum->setDate(
//                    $registratie->getDatum()->format("Y"),
//                    $registratie->getDatum()->format("m"),
//                    $registratie->getDatum()->format("d")
//                );
                $datum = $registratie->getDatum();
            }
        }

        //Deze manier om laatste dag van die maand te berekenen vervangen door last day of ...
//        $yearMonth = $datum->format('Y-m');
//        while ($yearMonth == $datum->format('Y-m')) {
//            $datum->modify('+1 day');
//        }
//        $datum->modify('-1 day');


        $this->datum = new \DateTime("last day of ".$datum->format("M Y"));
    }
}
