<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Entity\NameTrait;
use AppBundle\Entity\AddressTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Entity\Stadsdeel;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Geslacht;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_klanten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Klant implements MemoSubjectInterface, DocumentSubjectInterface
{
    use NameTrait, AddressTrait, RequiredMedewerkerTrait, MemoSubjectTrait, DocumentSubjectTrait, TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Geslacht
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geslacht")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $geslacht;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $werkgebied;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $inschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $uitschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $laatsteContact;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $actief = true;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    private $onHold = false;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $bewindvoerder;

    /**
     * @var ArrayCollection|Klus[]
     * @ORM\OneToMany(targetEntity="Klus", mappedBy="klant")
     */
    private $klussen;

    /**
     * @var ArrayCollection|Factuur[]
     * @ORM\OneToMany(targetEntity="Factuur", mappedBy="klant")
     */
    private $facturen;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Gedmo\Versioned
     */
    private $saldo = 0.0;

    public function __construct()
    {
        $this->klussen = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getGeslacht()
    {
        return $this->geslacht;
    }

    public function setGeslacht(Geslacht $geslacht)
    {
        $this->geslacht = $geslacht;

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
        return $this->actief;
    }

    public function setActief($actief)
    {
        $this->actief = $actief;

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

    public function getFacturen()
    {
        return $this->facturen;
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

    public function getUitschrijving()
    {
        return $this->uitschrijving;
    }

    public function setUitschrijving($uitschrijving)
    {
        $this->uitschrijving = $uitschrijving;
        return $this;
    }

    public function getLaatsteContact()
    {
        return $this->laatsteContact;
    }

    public function setLaatsteContact($laatsteContact)
    {
        $this->laatsteContact = $laatsteContact;
        return $this;
    }

    public function getBewindvoerder()
    {
        return $this->bewindvoerder;
    }

    public function setBewindvoerder($bewindvoerder)
    {
        $this->bewindvoerder = $bewindvoerder;

        return $this;
    }

    public function getWerkgebied()
    {
        return $this->werkgebied;
    }

    public function setWerkgebied($werkgebied)
    {
        $this->werkgebied = $werkgebied;

        return $this;
    }

    public function getSaldo()
    {
        return $this->saldo;
    }

    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }
}
