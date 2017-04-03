<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant as AppKlant;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_dienstverleners")
 * @ORM\HasLifecycleCallbacks
 */
class Dienstverlener implements MemoSubjectInterface, DocumentSubjectInterface
{
    use MemoSubjectTrait, DocumentSubjectTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $inschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $uitschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $laatsteContact;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $actief = true;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $modified;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $onHold = false;

    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $klant;

    /**
     * @var ArrayCollection|Klus[]
     * @ORM\OneToMany(targetEntity="Klus", mappedBy="klant")
     */
    private $klussen;

//     /**
//      * @var ArrayCollection|Memo[]
//      *
//      * @ORM\ManyToMany(targetEntity="Memo", cascade={"persist", "remove"})
//      * @ORM\JoinTable(name="hs_klant_memo", inverseJoinColumns={@ORM\JoinColumn(unique=true, onDelete="CASCADE")})
//      */
//     protected $memos;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = $this->modified = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->modified = new \DateTime();
    }

    public function __construct(AppKlant $klant = null)
    {
        if ($klant) {
            $this->klant = $klant;
        }
        $this->klussen = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->klant;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(AppKlant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getInschrijving()
    {
        return $this->inschrijving;
    }

    public function setInschrijving(\DateTime $inschrijving)
    {
        $this->inschrijving = $inschrijving;

        return $this;
    }

    public function getKlussen()
    {
        return $this->klussen;
    }

    public function isDeletable()
    {
        return count($this->klussen) === 0;
    }

    public function isActief()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('einddatum'))
            ->orWhere(Criteria::expr()->gte('einddatum', new \DateTime('today')));

        $actief = count($this->klussen->matching($criteria)) > 0;
        $this->setActief($actief);

        return $actief;
    }

    public function setActief($actief)
    {
        $this->actief = $actief;

        return $this;
    }

    public function getGefactureerd()
    {
        $bedrag = 0.0;
        foreach ($this->klussen as $klus) {
            $bedrag += $klus->getGefactureerd();
        }

        return $bedrag;
    }

    public function getBetaald()
    {
        $bedrag = 0.0;
        foreach ($this->klussen as $klus) {
            $bedrag += $klus->getBetaald();
        }

        return $bedrag;
    }

    public function getOpenstaand()
    {
        return $this->getGefactureerd() - $this->getBetaald();
    }

    public function getFacturen()
    {
        $facturen = new ArrayCollection();
        foreach ($this->klussen as $klus) {
            foreach ($klus->getFacturen() as $factuur) {
                $facturen->add($factuur);
            }
        }

        return $facturen;
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
