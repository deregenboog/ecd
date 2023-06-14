<?php

namespace HsBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use HsBundle\Exception\HsException;
use HsBundle\Exception\InvoiceLockedException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_registraties")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks
 */
class Registratie implements FactuurSubjectInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @var \DateTime
     * @ORM\Column(type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $start;

    /**
     * @var \DateTime
     * @ORM\Column(type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $eind;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"default":0})
     * @Gedmo\Versioned
     */
    private $dagdelen = 0;

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
     * @ORM\JoinColumn(onDelete="CASCADE")
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
     * @ORM\ManyToOne(targetEntity="Activiteit")
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
        if ($this->datum->format("Y") <= '2022') {
            return $this->datum->format('d-m-Y') .' | '
                .$this->start->format('H:i').' - '
                .$this->eind->format('H:i')
                ;
        } else {
            return $this->datum->format('d-m-Y')
                ;
        }
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
        if ($this->datum->format("Y") <= 2022) {
            if (!$this->start || !$this->eind) {
                return 0;
            }

            $seconds = $this->eind->getTimestamp() - $this->start->getTimestamp();

            return $seconds / 3600;
        } else {
            return ($this->dagdelen * 2);//1 dagdeel = 2 uur.
        }
    }

    public function getDagdelen()
    {
        if ($this->datum->format("Y") <= '2022') {
            return $this->getUren() > 3 ? 2 : 1;
        } else {
            return $this->dagdelen;
        }
    }

    /**
     * @param int $dagdelen
     */
    public function setDagdelen(int $dagdelen): self
    {
        if ($this->datum->format("Y") <= "2022") {
            throw new HsException("Kan geen dagdelen instellen op registraties voor 2023.");
        }
        $this->dagdelen = $dagdelen;
        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->datum->format("Y") > '2022' && $this->dagdelen == 0) {
            $context->buildViolation('Het is verplicht het aantal dagdelen van deze registratie in te stellen.')
                ->atPath('dagdelen')
                ->addViolation();
        } elseif ($this->datum->format("Y") <= '2022' && ($this->start == null || $this->eind == null)) {
            $context->buildViolation('Het is verplicht het de start en eindtijd van deze registratie in te stellen.')
                ->atPath('start')
                ->addViolation();
        }
    }
}
