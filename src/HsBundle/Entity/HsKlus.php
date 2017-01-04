<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Medewerker;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_klussen")
 */
class HsKlus extends HsMemoSubject
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * Datum van uitvoering.
     *
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    private $datum;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    private $startdatum;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $einddatum;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $onHold = false;

    /**
     * @var HsKlant
     * @ORM\ManyToOne(targetEntity="HsKlant", inversedBy="hsKlussen")
     */
    private $hsKlant;

    /**
     * @var HsActiviteit
     * @ORM\ManyToOne(targetEntity="HsActiviteit", inversedBy="hsKlussen")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hsActiviteit;

    private $hsVrijwilliger;

    /**
     * @var ArrayCollection|HsVrijwilliger[]
     * @ORM\ManyToMany(targetEntity="HsVrijwilliger", inversedBy="hsKlussen")
     */
    private $hsVrijwilligers;

    /**
     * @var ArrayCollection|HsRegistratie[]
     * @ORM\OneToMany(targetEntity="HsRegistratie", mappedBy="hsKlus")
     */
    private $hsRegistraties;

    /**
     * @var ArrayCollection|HsDeclaratie[]
     * @ORM\OneToMany(targetEntity="HsDeclaratie", mappedBy="hsKlus")
     */
    private $hsDeclaraties;

    /**
     * @var ArrayCollection|HsFactuur[]
     * @ORM\OneToMany(targetEntity="HsFactuur", mappedBy="hsKlus")
     */
    private $hsFacturen;

    /**
     * @var ArrayCollection|HsMemo[]
     *
     * @ORM\ManyToMany(targetEntity="HsMemo", cascade={"persist", "remove"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true, onDelete="CASCADE")})
     */
    protected $hsMemos;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medewerker;

    public function __construct(HsKlant $hsKlant = null, Medewerker $medewerker = null)
    {
        $this->hsKlant = $hsKlant;
        $this->medewerker = $medewerker;
        $this->hsVrijwilligers = new ArrayCollection();
        $this->hsRegistraties = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('Klus #%d',$this->id);
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

    public function getHsKlant()
    {
        return $this->hsKlant;
    }

    public function setHsKlant(HsKlant $hsKlant)
    {
        $this->hsKlant = $hsKlant;

        return $this;
    }

    public function getHsActiviteit()
    {
        return $this->hsActiviteit;
    }

    public function setHsActiviteit(HsActiviteit $hsActiviteit)
    {
        $this->hsActiviteit = $hsActiviteit;

        return $this;
    }

    public function getHsVrijwilligers()
    {
        return $this->hsVrijwilligers;
    }

    public function getHsVrijwilliger()
    {
        return null;
    }

    public function setHsVrijwilliger(HsVrijwilliger $hsVrijwilliger)
    {
        return $this->addHsVrijwilliger($hsVrijwilliger);
    }

    public function addHsVrijwilliger($hsVrijwilliger)
    {
        $this->hsVrijwilligers->add($hsVrijwilliger);
        $hsVrijwilliger->getHsKlussen()->add($this);

        return $this;
    }

    public function removeHsVrijwilliger($hsVrijwilliger)
    {
        $this->hsVrijwilligers->removeElement($hsVrijwilliger);
        $hsVrijwilliger->getHsKlussen()->removeElement($this);

        return $this;
    }

    public function getHsRegistraties()
    {
        return $this->hsRegistraties;
    }

    public function getHsDeclaraties()
    {
        return $this->hsDeclaraties;
    }

    public function getHsFacturen()
    {
        return $this->hsFacturen;
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

    public function isDeletable()
    {
        return count($this->hsFacturen) === 0
            && count($this->hsRegistraties) === 0;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum)
    {
        $this->startdatum = $startdatum;
        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum(\DateTime $einddatum)
    {
        $this->einddatum = $einddatum;
        return $this;
    }

    public function getGefactureerd()
    {
        $bedrag = 0.0;
        foreach ($this->hsFacturen as $hsFactuur) {
            $bedrag += $hsFactuur->getBedrag();
        }

        return $bedrag;
    }

    public function getBetaald()
    {
        $bedrag = 0.0;
        foreach ($this->hsFacturen as $hsFactuur) {
            $bedrag += $hsFactuur->getBetaald();
        }

        return $bedrag;
    }

    public function getOpenstaand()
    {
        return $this->getGefactureerd() - $this->getBetaald();
    }

    public function isOnHold()
    {
        return $this->onHold;
    }

    public function setOnHold($onHold)
    {
        $this->onHold = $onHold;

        return $this;
    }
}
