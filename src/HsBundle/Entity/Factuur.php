<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_facturen")
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
     */
    private $nummer;

    /**
     * @ORM\Column(type="date")
     */
    private $datum;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $betreft;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $bedrag;

    /**
     * @var Klus
     * @ORM\ManyToOne(targetEntity="Klus", inversedBy="facturen")
     */
    private $klus;

    /**
     * @var ArrayCollection|Registratie[]
     * @ORM\OneToMany(targetEntity="Registratie", mappedBy="factuur", orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $registraties;

    /**
     * @var ArrayCollection|Declaratie[]
     * @ORM\OneToMany(targetEntity="Declaratie", mappedBy="factuur", orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $declaraties;

    /**
     * @var ArrayCollection|Betaling[]
     * @ORM\OneToMany(targetEntity="Betaling", mappedBy="factuur")
     */
    private $betalingen;

    public function __construct(Klus $klus = null)
    {
        $this->setDatum(new \DateTime());
        $this->setKlus($klus);

        $firstDayOfPrevMonth = new \DateTime('first day of previous month');
        $lastDayOfPrevMonth = new \DateTime('last day of previous month');
        $lastDayOfPrevMonth = new \DateTime('last day of this month');

        $this->nummer = sprintf(
            '%d/%d',
            $klus->getKlant()->getId(),
            $lastDayOfPrevMonth->format('ymd')
        );

        $this->betreft = sprintf(
            'Factuurnr: %s van %s t/m %s',
            $this->nummer,
            $firstDayOfPrevMonth->format('d-m-Y'),
            $lastDayOfPrevMonth->format('d-m-Y')
        );

        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('factuur'))
            ->andWhere(Criteria::expr()->lte('datum', $lastDayOfPrevMonth))
        ;
        $this->setRegistraties($klus->getRegistraties()->matching($criteria));
        $this->setDeclaraties($klus->getDeclaraties()->matching($criteria));

        $this->calculate();
    }

    public function __toString()
    {
        return $this->nummer;
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

    public function getKlus()
    {
        return $this->klus;
    }

    private function setKlus(Klus $klus)
    {
        $this->klus = $klus;
        $klus->getFacturen()->add($this);

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

    private function setRegistraties(ArrayCollection $registraties)
    {
        $this->registraties = $registraties;
        foreach ($registraties as $registratie) {
            $registratie->setFactuur($this);
        }

        return $this;
    }

    public function getDeclaraties()
    {
        return $this->declaraties;
    }

    private function setDeclaraties(ArrayCollection $declaraties)
    {
        $this->declaraties = $declaraties;
        foreach ($declaraties as $declaratie) {
            $declaratie->setFactuur($this);
        }

        return $this;
    }

    private function calculate()
    {
        $bedrag = 0.0;

        foreach ($this->registraties as $registratie) {
            $bedrag += 2.5 * $registratie->getUren();
        }

        foreach ($this->declaraties as $declaratie) {
            $bedrag += $declaratie->getBedrag();
        }

        $this->bedrag = $bedrag;

        return $this;
    }

    public function getBedrag()
    {
        return $this->bedrag;
    }

    public function isDeletable()
    {
        // @todo
        return true;
    }

    public function getBetalingen()
    {
        return $this->betalingen;
    }

    public function getBetaald()
    {
        $betaald = 0.0;
        foreach ($this->betalingen as $betaling) {
            $betaald += $betaling->getBedrag();
        }

        return $betaald;
    }

    public function getOpenstaand()
    {
        return $this->bedrag - $this->getBetaald();
    }

    public function getBetreft()
    {
        return $this->betreft;
    }

}
