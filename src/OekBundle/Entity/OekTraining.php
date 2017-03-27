<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="OekBundle\Repository\OekTrainingRepository")
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
     * @var ArrayCollection|OekDeelname[]
     * @ORM\OneToMany(targetEntity="OekDeelname", mappedBy="oekTraining")
     */
    private $oekDeelnames;

    /**
     * @var OekGroep
     * @ORM\ManyToOne(targetEntity="OekGroep", inversedBy="oekTrainingen")
     * @ORM\JoinColumn(nullable=false)
     */
    private $oekGroep;

    public function __construct()
    {
        $this->oekDeelnames = new ArrayCollection();
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

    public function getOekDeelnames()
    {
        return $this->oekDeelnames;
    }

    public function getOekKlanten()
    {
        $oekKlanten = new ArrayCollection();
        foreach ($this->oekDeelnames as $oekDeelname) {
            $oekKlanten[] = $oekDeelname->getOekKlant();
        }

        return $oekKlanten;
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
        return $this->oekDeelnames->count() == 0;
    }
}
