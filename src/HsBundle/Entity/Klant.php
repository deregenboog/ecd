<?php

namespace HsBundle\Entity;

use AppBundle\Entity\Geslacht;
use AppBundle\Model\AddressTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="hs_klanten", uniqueConstraints={
 *
 *     @ORM\UniqueConstraint(name="unique_erp_id_idx", columns={"erp_id"})
 * })
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Klant implements MemoSubjectInterface, DocumentSubjectInterface
{
    use IdentifiableTrait;
    use NameTrait;
    use AddressTrait;
    use HulpverlenerTrait;
    use RequiredMedewerkerTrait;
    use MemoSubjectTrait;
    use DocumentSubjectTrait;
    use TimestampableTrait;

    public const STATUS_OK = 1;
    public const STATUS_GEEN_NIEUWE_KLUS = 2;

    /**
     * @ORM\Column(name="erp_id", type="integer", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $erpId;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $bsn;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $rekeningnummer;

    /**
     * @var Geslacht
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Geslacht")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $geslacht;

    /**
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    private $inschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $uitschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $laatsteContact;

    /**
     * !LET OP: actief betekent in de context van HS: heeft geen lopende/openstaande klussen. Dus niet een soort 'verwijderen'.
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $actief = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $postcode;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GgwGebied")
     *
     * @ORM\JoinColumn(name="postcodegebied", referencedColumnName="naam")
     *
     * @Gedmo\Versioned
     */
    private $postcodegebied;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    protected static $statussen = [
        'Nieuwe klussen mogelijk' => self::STATUS_OK,
        'Geen nieuwe klussen' => self::STATUS_GEEN_NIEUWE_KLUS,
    ];

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default":1})
     *
     * @Gedmo\Versioned
     */
    private $status = self::STATUS_OK;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $bewindvoerder;

    /**
     * @var ArrayCollection|Klus[]
     *
     * @ORM\OneToMany(targetEntity="Klus", mappedBy="klant", cascade={"persist"})
     *
     * @ORM\OrderBy({"startdatum": "desc", "einddatum": "desc", "id": "desc"})
     */
    private $klussen;

    /**
     * @var ArrayCollection|Factuur[]
     *
     * @ORM\OneToMany(targetEntity="Factuur", mappedBy="klant", cascade={"persist"})
     */
    private $facturen;

    /**
     * @ORM\Column(type="decimal", scale=2)
     *
     * @Gedmo\Versioned
     */
    private $saldo = 0.0;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $afwijkendFactuuradres = false;

    public function __construct()
    {
        $this->klussen = new ArrayCollection();
        $this->facturen = new ArrayCollection();
        $this->inschrijving = new \DateTime('now');
    }

    public function __toString()
    {
        $naam = '';

        if ($this->achternaam) {
            $naam .= $this->achternaam;
            if ($this->tussenvoegsel || $this->voornaam) {
                $naam .= ', ';
            }
        }

        if ($this->voornaam) {
            $naam .= $this->voornaam;
            if ($this->tussenvoegsel) {
                $naam .= ' ';
            }
        }

        if ($this->tussenvoegsel) {
            $naam .= $this->tussenvoegsel;
        }

        return $naam;
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

        $this->updateStatus();

        return $this;
    }

    public function isDeletable()
    {
        return 0 === count($this->klussen)
            && 0 === count($this->facturen)
            && 0 === count($this->memos)
        ;
    }

    public function isActief()
    {
        return $this->actief;
    }

    public function setActief($actief)
    {
        $this->actief = (bool) $actief;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getStatusAsString(): string
    {
        return array_search($this->status, self::$statussen);
    }

    public function getStatussen(): array
    {
        return self::$statussen;
    }

    public function setStatus(int $status): Klant
    {
        $this->status = $status;

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

    public function addFactuur(Factuur $factuur)
    {
        $this->facturen[] = $factuur;
        $factuur->setKlant($this);

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

    public function isAfwijkendFactuuradres()
    {
        return (bool) $this->afwijkendFactuuradres;
    }

    public function setAfwijkendFactuuradres($afwijkendFactuuradres)
    {
        $this->afwijkendFactuuradres = (bool) $afwijkendFactuuradres;

        return $this;
    }

    public function updateStatus()
    {
        $actief = false;
        foreach ($this->klussen as $klus) {
            if (in_array($klus->getStatus(), [Klus::STATUS_OPENSTAAND, Klus::STATUS_IN_BEHANDELING])) {
                $actief = true;
                break;
            }
        }

        $this->setActief($actief);
    }
}
