<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_trainingen")
 * @ORM\HasLifecycleCallbacks
 */
class OekTraining
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $naam;

    /**
     * @ORM\Column(type="date")
     */
    private $startdatum;

    /**
     * @ORM\Column(type="time")
     */
    private $starttijd;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $einddatum;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $locatie;

    /**
     * @var ArrayCollection|OekKlant[]
     * @ORM\ManyToMany(targetEntity="OekKlant", inversedBy="oekTrainingen")
     */
    private $oekKlanten;

    /**
     * @var OekGroep
     * @ORM\ManyToOne(targetEntity="OekGroep", inversedBy="oekTrainingen")
     * @ORM\JoinColumn(nullable=false)
     */
    private $oekGroep;

    public function __construct()
    {
        $this->oekKlanten = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->naam, $this->oekGroep);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    public function getLocatie()
    {
        return $this->locatie;
    }

    public function setLocatie($locatie)
    {
        $this->locatie = $locatie;

        return $this;
    }

    public function getStartDatum()
    {
        return $this->startdatum;
    }

    public function setStartDatum($startdatum)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getStartTijd()
    {
        return $this->starttijd;
    }

    public function setStartTijd($starttijd)
    {
        $this->starttijd = $starttijd;

        return $this;
    }

    public function getEindDatum()
    {
        return $this->einddatum;
    }

    public function setEindDatum($einddatum)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getOekKlanten()
    {
        return $this->oekKlanten;
    }

    public function setOekKlanten($oekKlanten = [])
    {
        $this->oekKlanten = $oekKlanten;

        return $this;
    }

    public function addOekKlant(OekKlant $oekKlant)
    {
        $oekKlant->addOekTraining($this);
        $this->oekKlanten->add($oekKlant);

        $this->oekGroep->getOekKlanten()->removeElement($oekKlant);

        return $this;
    }

    public function removeOekKlant(OekKlant $oekKlant)
    {
        if ($this->oekKlanten->contains($oekKlant)) {
            $oekKlant->removeOekTraining($this);
            $this->oekKlanten->removeElement($oekKlant);
        }

        return $this;
    }

    public function getOekGroep()
    {
        return $this->oekGroep;
    }

    public function setOekGroep(OekGroep $oekGroep)
    {
        $this->oekGroep = $oekGroep;

        return $this;
    }

    public function isDeletable()
    {
        return $this->oekKlanten->count() == 0;
    }
}
