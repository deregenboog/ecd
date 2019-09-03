<?php

namespace OekBundle\Entity;

use AppBundle\Model\KlantRelationInterface;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="OekBundle\Repository\TrainingRepository")
 * @ORM\Table(name="oek_trainingen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Training implements KlantRelationInterface
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
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\Column(type="time")
     * @Gedmo\Versioned
     */
    private $starttijd;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $locatie;

    /**
     * @var ArrayCollection|Deelname[]
     * @ORM\OneToMany(targetEntity="Deelname", mappedBy="training")
     */
    private $deelnames;

    /**
     * @var Groep
     * @ORM\ManyToOne(targetEntity="Groep", inversedBy="trainingen")
     * @ORM\JoinColumn(name="oekGroep_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $groep;

    public function __construct()
    {
        $this->deelnames = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->naam, $this->groep);
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

    public function getDeelname(Deelnemer $deelnemer)
    {
        foreach ($this->deelnames as $deelname) {
            if ($deelname->getDeelnemer() == $deelnemer) {
                return $deelname;
            }
        }
    }

    public function getDeelnames()
    {

        foreach($this->deelnames as $dn)
        {
            if($dn->getStatus() == DeelnameStatus::STATUS_VERWIJDERD) continue;
            $this->deelnames->removeElement($dn);
        }
        return $this->deelnames;
    }

    public function getDeelnemers()
    {
        $deelnemers = new ArrayCollection();
        foreach ($this->deelnames as $deelname) {
            if($deelname->getStatus() == DeelnameStatus::STATUS_VERWIJDERD) continue;
            $deelnemers->add($deelname->getDeelnemer());
        }

        return $deelnemers;
    }

    public function getGroep()
    {
        return $this->groep;
    }

    public function setGroep(Groep $groep)
    {
        $this->groep = $groep;

        return $this;
    }

    public function isDeletable()
    {
        return 0 == $this->deelnames->count();
    }

    public function getKlant()
    {
        return $this->getDeelnames();
    }
    public function getKlantFieldName()
    {
        return "Deelnames";
    }
}
