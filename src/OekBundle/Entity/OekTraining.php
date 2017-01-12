<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_klanten")
 * @ORM\HasLifecycleCallbacks
 */
class OekKlant extends HsMemoSubject
{
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
     * @var ArrayCollection|HsKlus[]
     * @ORM\OneToMany(targetEntity="HsKlus", mappedBy="hsKlant")
     */
    private $hsKlussen;

    /**
     * @var ArrayCollection|HsMemo[]
     *
     * @ORM\ManyToMany(targetEntity="HsMemo", cascade={"persist", "remove"})
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true, onDelete="CASCADE")})
     */
    protected $hsMemos;

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

    public function __construct()
    {
        $this->hsKlussen = new ArrayCollection();
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

    public function setKlant(Klant $klant)
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

    public function getHsKlussen()
    {
        return $this->hsKlussen;
    }

    public function isDeletable()
    {
        return count($this->hsKlussen) === 0;
    }

    public function isActief()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('einddatum'))
            ->orWhere(Criteria::expr()->gte('einddatum', new \DateTime('today')));

        $actief = count($this->hsKlussen->matching($criteria)) > 0;
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
        foreach ($this->hsKlussen as $hsKlus) {
            $bedrag += $hsKlus->getGefactureerd();
        }

        return $bedrag;
    }

    public function getBetaald()
    {
        $bedrag = 0.0;
        foreach ($this->hsKlussen as $hsKlus) {
            $bedrag += $hsKlus->getBetaald();
        }

        return $bedrag;
    }

    public function getOpenstaand()
    {
        return $this->getGefactureerd() - $this->getBetaald();
    }

    public function getHsFacturen()
    {
        $hsFacturen = new ArrayCollection();
        foreach ($this->hsKlussen as $hsKlus) {
            foreach ($hsKlus->getHsFacturen() as $hsFactuur) {
                $hsFacturen->add($hsFactuur);
            }
        }

        return $hsFacturen;
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
