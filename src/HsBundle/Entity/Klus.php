<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Medewerker;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_klussen")
 * @Gedmo\Loggable
 */
class Klus implements MemoSubjectInterface
{
    use MemoSubjectTrait;

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
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $onHold = false;

    /**
     * @var Klant
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="klussen")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Activiteit
     * @ORM\ManyToOne(targetEntity="Activiteit", inversedBy="klussen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $activiteit;

    private $dienstverlener;

    private $vrijwilliger;

    /**
     * @var ArrayCollection|Dienstverlener[]
     * @ORM\ManyToMany(targetEntity="Dienstverlener", inversedBy="klussen")
     */
    private $dienstverleners;

    /**
     * @var ArrayCollection|Vrijwilliger[]
     * @ORM\ManyToMany(targetEntity="Vrijwilliger", inversedBy="klussen")
     */
    private $vrijwilligers;

    /**
     * @var ArrayCollection|Registratie[]
     * @ORM\OneToMany(targetEntity="Registratie", mappedBy="klus")
     */
    private $registraties;

    /**
     * @var ArrayCollection|Declaratie[]
     * @ORM\OneToMany(targetEntity="Declaratie", mappedBy="klus")
     */
    private $declaraties;

    /**
     * @var ArrayCollection|Factuur[]
     * @ORM\ManyToMany(targetEntity="Factuur", mappedBy="klussen")
     */
    private $facturen;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $medewerker;

    public function __construct(Klant $klant = null, Medewerker $medewerker = null)
    {
        $this->klant = $klant;
        $this->medewerker = $medewerker;
        $this->dienstverleners = new ArrayCollection();
        $this->vrijwilligers = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->facturen = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s %d', $this->activiteit, $this->id);
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

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getActiviteit()
    {
        return $this->activiteit;
    }

    public function setActiviteit(Activiteit $activiteit)
    {
        $this->activiteit = $activiteit;

        return $this;
    }

    public function getDienstverleners()
    {
        return $this->dienstverleners;
    }

    public function addDienstverlener($dienstverlener)
    {
        $this->dienstverleners->add($dienstverlener);
        $dienstverlener->getKlussen()->add($this);

        return $this;
    }

    public function removeDienstverlener($dienstverlener)
    {
        $this->dienstverleners->removeElement($dienstverlener);
        $dienstverlener->getKlussen()->removeElement($this);

        return $this;
    }

    public function getVrijwilligers()
    {
        return $this->vrijwilligers;
    }

    public function addVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        $this->vrijwilligers->add($vrijwilliger);
        $vrijwilliger->getKlussen()->add($this);

        return $this;
    }

    public function removeVrijwilliger($vrijwilliger)
    {
        $this->vrijwilligers->removeElement($vrijwilliger);
        $vrijwilliger->getKlussen()->removeElement($this);

        return $this;
    }

    public function getRegistraties()
    {
        return $this->registraties;
    }

    public function getDeclaraties()
    {
        return $this->declaraties;
    }

    public function getFacturen()
    {
        return $this->facturen;
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
        return true;

        // @todo

        return count($this->facturen) === 0
            && count($this->registraties) === 0;
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
        foreach ($this->facturen as $factuur) {
            $bedrag += $factuur->getBedrag();
        }

        return $bedrag;
    }

    public function getBetaald()
    {
        $bedrag = 0.0;
        foreach ($this->facturen as $factuur) {
            $bedrag += $factuur->getBetaald();
        }

        return $bedrag;
    }

    public function getSaldo()
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
