<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_facturen")
 */
class HsFactuur
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
     * @var HsKlus
     * @ORM\ManyToOne(targetEntity="HsKlus", inversedBy="hsFacturen")
     */
    private $hsKlus;

    /**
     * @var ArrayCollection|HsRegistratie[]
     * @ORM\OneToMany(targetEntity="HsRegistratie", mappedBy="hsFactuur", orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $hsRegistraties;

    /**
     * @var ArrayCollection|HsDeclaratie[]
     * @ORM\OneToMany(targetEntity="HsDeclaratie", mappedBy="hsFactuur", orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $hsDeclaraties;

    /**
     * @var ArrayCollection|HsBetaling[]
     * @ORM\OneToMany(targetEntity="HsBetaling", mappedBy="hsFactuur")
     */
    private $hsBetalingen;

    public function __construct(HsKlus $hsKlus = null)
    {
        $this->setDatum(new \DateTime());
        $this->setHsKlus($hsKlus);

        $firstDayOfPrevMonth = new \DateTime('first day of previous month');
        $lastDayOfPrevMonth = new \DateTime('last day of previous month');
        $lastDayOfPrevMonth = new \DateTime('last day of this month');

        $this->nummer = sprintf(
            '%d/%d',
            $hsKlus->getHsKlant()->getId(),
            $lastDayOfPrevMonth->format('ymd')
        );

        $this->betreft = sprintf(
            'Factuurnr: %s van %s t/m %s',
            $this->nummer,
            $firstDayOfPrevMonth->format('d-m-Y'),
            $lastDayOfPrevMonth->format('d-m-Y')
        );

        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('hsFactuur'))
            ->andWhere(Criteria::expr()->lte('datum', $lastDayOfPrevMonth))
        ;
        $this->setHsRegistraties($hsKlus->getHsRegistraties()->matching($criteria));
        $this->setHsDeclaraties($hsKlus->getHsDeclaraties()->matching($criteria));

        $this->calculate();
    }

    public function __toString()
    {
        return sprintf('Factuur %d', $this->id);
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

    public function getHsKlus()
    {
        return $this->hsKlus;
    }

    private function setHsKlus(HsKlus $hsKlus)
    {
        $this->hsKlus = $hsKlus;
        $hsKlus->getHsFacturen()->add($this);

        return $this;
    }

    public function getHsKlant()
    {
        return $this->hsKlant;
    }

    public function setHsKlant(HsKlant $hsKlant)
    {
        $this->hsKlant = $hsKlant;

        return $this;
    }

    public function getHsRegistraties()
    {
        return $this->hsRegistraties;
    }

    private function setHsRegistraties(ArrayCollection $hsRegistraties)
    {
        $this->hsRegistraties = $hsRegistraties;
        foreach ($hsRegistraties as $hsRegistratie) {
            $hsRegistratie->setHsFactuur($this);
        }

        return $this;
    }

    public function getHsDeclaraties()
    {
        return $this->hsDeclaraties;
    }

    private function setHsDeclaraties(ArrayCollection $hsDeclaraties)
    {
        $this->hsDeclaraties = $hsDeclaraties;
        foreach ($hsDeclaraties as $hsDeclaratie) {
            $hsDeclaratie->setHsFactuur($this);
        }

        return $this;
    }

    private function calculate()
    {
        $bedrag = 0.0;

        foreach ($this->hsRegistraties as $hsRegistratie) {
            $bedrag += 2.5 * $hsRegistratie->getUren();
        }

        foreach ($this->hsDeclaraties as $hsDeclaratie) {
            $bedrag += $hsDeclaratie->getBedrag();
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

    public function getHsBetalingen()
    {
        return $this->hsBetalingen;
    }

    public function getBetaald()
    {
        $betaald = 0.0;
        foreach ($this->hsBetalingen as $hsBetaling) {
            $betaald += $hsBetaling->getBedrag();
        }

        return $betaald;
    }

    public function getOpenstaand()
    {
        return $this->bedrag - $this->getBetaald();
    }
}
