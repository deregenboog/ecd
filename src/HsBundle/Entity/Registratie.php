<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medewerker;
use Gedmo\Mapping\Annotation as Gedmo;
use HsBundle\Exception\InvoiceLockedException;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_registraties")
 * @Gedmo\Loggable
 */
class Registratie implements FactuurSubjectInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @var \DateTime
     * @ORM\Column(type="time")
     * @Gedmo\Versioned
     */
    private $start;

    /**
     * @var \DateTime
     * @ORM\Column(type="time")
     * @Gedmo\Versioned
     */
    private $eind;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Gedmo\Versioned
     */
    private $reiskosten;

    /**
     * @var Klus
     * @ORM\ManyToOne(targetEntity="Klus", inversedBy="registraties")
     * @Gedmo\Versioned
     */
    private $klus;

    /**
     * @var Factuur
     * @ORM\ManyToOne(targetEntity="Factuur", inversedBy="registraties", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $factuur;

    /**
     * @var Arbeider
     * @ORM\ManyToOne(targetEntity="Arbeider", inversedBy="registraties")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $arbeider;

    /**
     * @var Activiteit
     * @ORM\ManyToOne(targetEntity="Activiteit", inversedBy="klussen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $activiteit;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $medewerker;

    public function __construct(Klus $klus = null, Arbeider $arbeider = null)
    {
        if ($klus) {
            $this->setKlus($klus);
        }
        if ($arbeider) {
            $this->arbeider = $arbeider;
        }
        $this->datum = new \DateTime('now');
    }

    public function __toString()
    {
        return $this->datum->format('d-m-Y').' | '
            .$this->start->format('H:i').' - '
            .$this->eind->format('H:i')
        ;
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
        if ($this->factuur && $this->factuur->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->datum = $datum;

        return $this;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setStart(\DateTime $start)
    {
        if ($this->factuur && $this->factuur->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->start = $start;

        return $this;
    }

    public function getEind()
    {
        return $this->eind;
    }

    public function setEind(\DateTime $eind)
    {
        if ($this->factuur && $this->factuur->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->eind = $eind;

        return $this;
    }

    public function getReiskosten()
    {
        return $this->reiskosten;
    }

    public function setReiskosten($reiskosten)
    {
        if ($this->factuur && $this->factuur->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->reiskosten = $reiskosten;

        return $this;
    }

    public function getKlus()
    {
        return $this->klus;
    }

    public function setKlus(Klus $klus)
    {
        if ($this->factuur && $this->factuur->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->klus = $klus;

        if (!$this->datum) {
            $this->datum = $klus->getStartdatum();
        }

        return $this;
    }

    public function getArbeider()
    {
        return $this->arbeider;
    }

    public function setArbeider(Arbeider $arbeider)
    {
        if ($this->factuur && $this->factuur->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->arbeider = $arbeider;

        return $this;
    }

    public function getFactuur()
    {
        return $this->factuur;
    }

    public function setFactuur(Factuur $factuur = null)
    {
        if ($this->factuur && $this->factuur->isLocked()) {
            throw new InvoiceLockedException();
        }

        $this->factuur = $factuur;

        return $this;
    }

    public function getActiviteit()
    {
        return $this->activiteit;
    }

    public function setActiviteit(Activiteit $activiteit)
    {
        if ($this->factuur && $this->factuur->isLocked()) {
            throw new InvoiceLockedException();
        }

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
