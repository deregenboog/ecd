<?php

namespace HsBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="hs_klussen")
 *
 * @Gedmo\Loggable
 *
 * @ORM\HasLifecycleCallbacks
 */
class Klus implements MemoSubjectInterface
{
    use MemoSubjectTrait;
    use DocumentSubjectTrait;
    use TimestampableTrait;

    public const STATUS_OPENSTAAND = 'Openstaand';
    public const STATUS_IN_BEHANDELING = 'In behandeling';
    public const STATUS_ON_HOLD = 'On hold';
    public const STATUS_AFGEROND = 'Afgerond';
    public const STATUS_GEANNULEERD = 'Geannuleerd';

    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $annuleringsdatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $onHoldTot;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $onHold = false;

    /**
     * @var string
     *
     * @ORM\Column(nullable=false)
     *
     * @Gedmo\Versioned
     *
     * @Assert\Choice(choices={
     *     Klus::STATUS_OPENSTAAND,
     *     Klus::STATUS_IN_BEHANDELING,
     *     Klus::STATUS_ON_HOLD,
     *     Klus::STATUS_AFGEROND,
     *     Klus::STATUS_GEANNULEERD
     * })
     */
    private $status = self::STATUS_OPENSTAAND;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="klussen", cascade={"persist"})
     *
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Activiteit[]
     *
     * @ORM\ManyToMany(targetEntity="Activiteit", inversedBy="klussen")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Count(min=1, minMessage="Selecteer tenminste één activiteit")
     */
    private $activiteiten;

    /**
     * @var ArrayCollection|Dienstverlener[]
     *
     * @ORM\ManyToMany(targetEntity="Dienstverlener", inversedBy="klussen")
     */
    private $dienstverleners;

    /**
     * @var ArrayCollection|Vrijwilliger[]
     *
     * @ORM\ManyToMany(targetEntity="Vrijwilliger", inversedBy="klussen")
     */
    private $vrijwilligers;

    /**
     * @var ArrayCollection|Registratie[]
     *
     * @ORM\OneToMany(targetEntity="Registratie", mappedBy="klus")
     */
    private $registraties;

    /**
     * @var ArrayCollection|Declaratie[]
     *
     * @ORM\OneToMany(targetEntity="Declaratie", mappedBy="klus", cascade={"persist"})
     */
    private $declaraties;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     *
     * @ORM\JoinTable(name="hs_klus_document", inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     */
    protected $documenten;

    /**
     * @var Memo[]
     *
     * @ORM\ManyToMany(targetEntity="Memo", cascade={"persist"})
     *
     * @ORM\JoinTable(name="hs_klus_memo", inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     *
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    protected $memos;

    public function __construct(?Klant $klant = null, ?Medewerker $medewerker = null)
    {
        $this->klant = $klant;
        $this->medewerker = $medewerker;
        $this->activiteiten = new ArrayCollection();
        $this->dienstverleners = new ArrayCollection();
        $this->vrijwilligers = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->declaraties = new ArrayCollection();
        $this->startdatum = new \DateTime('now');
    }

    public function __toString()
    {
        if ($this->klant) {
            return sprintf('%s - %s', $this->getActiviteitenAsString(), $this->klant);
        }

        return sprintf('%s - %s', $this->getActiviteitenAsString(), 'Homeservice-klus (zonder klant)');
    }

    public function getId()
    {
        return $this->id;
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

    public function getActiviteiten()
    {
        return $this->activiteiten;
    }

    public function getActiviteitenAsString()
    {
        $activiteiten = [];
        foreach ($this->activiteiten as $activiteit) {
            $activiteiten[] = (string) $activiteit;
        }

        return implode(', ', $activiteiten);
    }

    public function setActiviteiten(Collection $activiteiten)
    {
        $this->activiteiten = $activiteiten;

        return $this;
    }

    public function addActiviteit(Activiteit $activiteit)
    {
        $this->activiteiten[] = $activiteit;
        $activiteit->addKlus($this);

        return $this;
    }

    public function removeActiviteit(Activiteit $activiteit)
    {
        $this->activiteiten->removeElement($activiteit);
        $activiteit->getKlussen()->removeElement($this);

        return $this;
    }

    public function getDienstverleners()
    {
        return $this->dienstverleners;
    }

    public function addDienstverlener(Dienstverlener $dienstverlener)
    {
        if (!$this->dienstverleners->contains($dienstverlener)) {
            $this->dienstverleners[] = $dienstverlener;
            $dienstverlener->addKlus($this);
        }

        $this->updateStatus();

        return $this;
    }

    public function removeDienstverlener(Dienstverlener $dienstverlener)
    {
        if (0 === (is_array($dienstverlener->getRegistraties($this)) || $dienstverlener->getRegistraties($this) instanceof \Countable ? count($dienstverlener->getRegistraties($this)) : 0)) {
            $this->dienstverleners->removeElement($dienstverlener);
            $dienstverlener->getKlussen()->removeElement($this);
        }

        return $this;
    }

    public function getVrijwilligers()
    {
        return $this->vrijwilligers;
    }

    public function addVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        if (!$this->vrijwilligers->contains($vrijwilliger)) {
            $this->vrijwilligers[] = $vrijwilliger;
            $vrijwilliger->addKlus($this);
        }

        $this->updateStatus();

        return $this;
    }

    public function removeVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        if (0 === (is_array($vrijwilliger->getRegistraties($this)) || $vrijwilliger->getRegistraties($this) instanceof \Countable ? count($vrijwilliger->getRegistraties($this)) : 0)) {
            $this->vrijwilligers->removeElement($vrijwilliger);
            $vrijwilliger->getKlussen()->removeElement($this);
        }

        return $this;
    }

    public function getRegistraties()
    {
        return $this->registraties;
    }

    public function addRegistratie(Registratie $registratie)
    {
        $this->registraties[] = $registratie;
        $registratie->setKlus($this);

        if ($registratie->getArbeider() instanceof Dienstverlener) {
            $this->addDienstverlener($registratie->getArbeider());
        }

        if ($registratie->getArbeider() instanceof Vrijwilliger) {
            $this->addVrijwilliger($registratie->getArbeider());
        }

        return $this;
    }

    public function getDeclaraties()
    {
        return $this->declaraties;
    }

    public function addDeclaratie(Declaratie $declaratie)
    {
        $this->declaraties[] = $declaratie;
        $declaratie->setKlus($this);

        return $this;
    }

    public function getFacturen()
    {
        $facturen = new ArrayCollection();

        foreach ($this->declaraties as $declaratie) {
            if (!$facturen->contains($declaratie->getFactuur())) {
                $facturen->add($declaratie->getFactuur());
            }
        }

        foreach ($this->registraties as $registratie) {
            if (!$facturen->contains($registratie->getFactuur())) {
                $facturen->add($registratie->getFactuur());
            }
        }

        return $facturen;
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
        return 0 === count($this->declaraties)
            && 0 === count($this->dienstverleners)
            && 0 === count($this->memos)
            && 0 === count($this->registraties)
            && 0 === count($this->vrijwilligers);
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

    public function setEinddatum(?\DateTime $einddatum = null)
    {
        $this->einddatum = $einddatum;

        $this->updateStatus();

        return $this;
    }

    public function getAnnuleringsdatum()
    {
        return $this->annuleringsdatum;
    }

    public function setAnnuleringsdatum(?\DateTime $annuleringsdatum = null)
    {
        $this->annuleringsdatum = $annuleringsdatum;

        $this->updateStatus();

        return $this;
    }

    public function getGefactureerd()
    {
        $bedrag = 0.0;
        foreach ($this->getFacturen() as $factuur) {
            $bedrag += $factuur->getBedrag();
        }

        return $bedrag;
    }

    public function getBetaald()
    {
        $bedrag = 0.0;
        foreach ($this->getFacturen() as $factuur) {
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

        $this->updateStatus();

        return $this;
    }

    public function getOnHoldTot(): ?\DateTime
    {
        return $this->onHoldTot;
    }

    public function setOnHoldTot(?\DateTime $onHoldTot): Klus
    {
        $this->onHoldTot = $onHoldTot;
        $this->updateStatus();

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    private function updateStatus()
    {
        if ($this->annuleringsdatum instanceof \DateTime && $this->annuleringsdatum <= new \DateTime('today')) {
            $this->status = self::STATUS_GEANNULEERD;
        } elseif ($this->einddatum instanceof \DateTime && $this->einddatum <= new \DateTime('today')) {
            $this->status = self::STATUS_AFGEROND;
        } elseif ($this->isOnHold()) {
            $this->status = self::STATUS_ON_HOLD;
        } elseif (count($this->dienstverleners) > 0 || count($this->vrijwilligers) > 0) {
            $this->status = self::STATUS_IN_BEHANDELING;
        } else {
            $this->status = self::STATUS_OPENSTAAND;
        }

        if ($this->klant) {
            $this->klant->updateStatus();
        }
    }
}
