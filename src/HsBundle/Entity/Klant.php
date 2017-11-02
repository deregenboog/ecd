<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Entity\NameTrait;
use AppBundle\Entity\AddressTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Werkgebied;
use AppBundle\Entity\GgwGebied;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="hs_klanten",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_erp_id_idx", columns={"erp_id"})}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Klant implements MemoSubjectInterface, DocumentSubjectInterface
{
    use NameTrait, AddressTrait, HulpverlenerTrait, RequiredMedewerkerTrait, MemoSubjectTrait, DocumentSubjectTrait, TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="erp_id", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $erpId;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $bsn;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $rekeningnummer;

    /**
     * @var Geslacht
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geslacht")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $geslacht;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Werkgebied")
     * @ORM\JoinColumn(name="stadsdeel", referencedColumnName="naam", nullable=true)
     * @Gedmo\Versioned
     */
    private $werkgebied;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GgwGebied")
     * @ORM\JoinColumn(name="postcodegebied", referencedColumnName="naam", nullable=true)
     * @Gedmo\Versioned
     */
    private $postcodegebied;

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
     * @ORM\OneToMany(targetEntity="Klus", mappedBy="klant", cascade={"persist"})
     * @ORM\OrderBy({"startdatum": "desc", "id": "desc"})
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
        $this->inschrijving = new \DateTime('now');
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

    public function addKlus(Klus $klus)
    {
        $this->klussen[] = $klus;
        $klus->setKlant($this);

        return $this;
    }

    public function isDeletable()
    {
        return 0 === count($this->klussen);
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

    public function setWerkgebied(Werkgebied $werkgebied = null)
    {
        $this->werkgebied = $werkgebied;

        return $this;
    }

    public function getPostcodegebied()
    {
        return $this->postcodegebied;
    }

    public function setPostcodegebied(GgwGebied $postcodegebied = null)
    {
        $this->postcodegebied = $postcodegebied;

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

    public function getErpId()
    {
        return $this->erpId;
    }

    public function setErpId($erpId)
    {
        $this->erpId = $erpId;

        return $this;
    }

    public function getRekeningnummer()
    {
        return $this->rekeningnummer;
    }

    public function setRekeningnummer($rekeningnummer)
    {
        $this->rekeningnummer = $rekeningnummer;

        return $this;
    }

    public function getBsn()
    {
        return $this->bsn;
    }

    public function setBsn($bsn)
    {
        $this->bsn = $bsn;

        return $this;
    }
}
