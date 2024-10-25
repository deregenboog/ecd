<?php

namespace VillaBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Exception\UserException;
use AppBundle\Model\DossierStatusTrait;
use AppBundle\Model\HasDossierStatusInterface;
use AppBundle\Model\HasDossierStatusTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\KlantRelationInterface;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use VillaBundle\Entity\DossierStatus;
use VillaBundle\Service\OvernachtingenCalculator;

/**
 * @ORM\Entity
 * @ORM\Table(name="villa_slapers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Slaper implements KlantRelationInterface, HasDossierStatusInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;
    use HasDossierStatusTrait;

    public const TYPE_RESPIJT = 1;
    public const TYPE_LOGEER = 2;

    public static $types = [
        self::TYPE_RESPIJT => "Respijtzorg",
        self::TYPE_LOGEER => "WMO Logeeropvang",
    ];

//
//    /**
//     * @var DossierStatus
//     *
//     * ORM\OneToOne(targetEntity="DossierStatus", cascade={"persist"})
//     * @Gedmo\Versioned
//     */
//    private $dossierStatus;

    /**
     * @var DossierStatus[]
     *
     * @ORM\OneToMany(targetEntity="DossierStatus", cascade={"persist"}, mappedBy="slaper", orphanRemoval="true")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $dossierStatussen;


    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $appKlant;

    /**
     * @var int
     *
     * @ORM\Column(name="slaperType", type="integer", options={"default":1})
     * @Gedmo\Versioned
     */
    private $type = self::TYPE_RESPIJT;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned()
     */
    private $contactpersoon;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned()
     */
    private $contactgegevensContactpersoon;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $redenSlapen;

    /**
     * @var Collection|Overnachting[]
     * @ORM\OneToMany(targetEntity="VillaBundle\Entity\Overnachting",cascade={"persist"}, mappedBy="slaper", orphanRemoval="true")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $overnachtingen;


    private $defaultOvernachtingsRecht = [
        Slaper::TYPE_LOGEER=>42,
        Slaper::TYPE_RESPIJT=>37,
    ];
    public function __construct(Klant $klant = null)
    {
        $this->overnachtingen = new ArrayCollection();
        $this->initializeTrait();
        $this->appKlant = $klant;

    }



    public function getOvernachtingsRechtForYear($year): int
    {
        switch($year)
        {
            case '2022':
                $overnachtingenRecht = [
                    Slaper::TYPE_LOGEER=>12,
                    Slaper::TYPE_RESPIJT=>17,
                ];
                break;
            case '2024':
                $overnachtingenRecht = [
                    Slaper::TYPE_LOGEER=>42,
                    Slaper::TYPE_RESPIJT=>37,
                ];
                break;
            default:
                $overnachtingenRecht = $this->defaultOvernachtingsRecht;
        }

        return $overnachtingenRecht[$this->getType()];
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatFormal($this->appKlant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getAppKlant(): ?Klant
    {
        return $this->appKlant;
    }

    public function setAppKlant(Klant $klant)
    {
        $this->appKlant = $klant;

        return $this;
    }



    public function isDeletable()
    {
        // @todo: implement for real
        return false;
    }

    public function getOpmerking()
    {
        return utf8_decode($this->opmerking);
    }

    public function setOpmerking($opmerking = null)
    {
        $this->opmerking = utf8_encode($opmerking);

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getTypeAsString(): string
    {
        return self::$types[$this->type];
    }

    /**
     * @return string
     */
    public function getContactpersoon(): ?string
    {
        return $this->contactpersoon;
    }

    /**
     * @param string $contactpersoon
     */
    public function setContactpersoon(?string $contactpersoon): void
    {
        $this->contactpersoon = $contactpersoon;
    }

    /**
     * @return string
     */
    public function getContactgegevensContactpersoon(): ?string
    {
        return $this->contactgegevensContactpersoon;
    }

    /**
     * @param string $contactgegevensContactpersoon
     */
    public function setContactgegevensContactpersoon(?string $contactgegevensContactpersoon): void
    {
        $this->contactgegevensContactpersoon = $contactgegevensContactpersoon;
    }

    /**
     * @return string
     */
    public function getRedenSlapen(): ?string
    {
        return $this->redenSlapen;
    }

    /**
     * @param string $redenSlapen
     */
    public function setRedenSlapen(?string $redenSlapen): void
    {
        $this->redenSlapen = $redenSlapen;
    }

    /**
     * @return Collection|Overnachting[]
     */
    public function getOvernachtingen()
    {
        return $this->overnachtingen;
    }

    /**
     * @param Collection|Overnachting[] $overnachtingen
     */
    public function setOvernachtingen($overnachtingen): void
    {
        $this->overnachtingen = $overnachtingen;
    }

    public function addOvernachting(Overnachting $overnachting): void
    {
        $this->overnachtingen->add($overnachting);
    }

    public function removeOvernachting(Overnachting $overnachting): void
    {
        if($this->overnachtingen->has($overnachting)){

            $this->overnachtingen->removeElement($overnachting);
        }
    }

    /**
     * Gets the balance of overnight stays.
     *
     * This method calculates the balance of overnight stays based on the number of
     * overnight stays within the last 12 months from the current date of the dossier.
     *
     * @return int The balance of overnight stays within the last 12 months.
     */
    public function getOvernachtingenUsed(): int
    {
        $nachtenGebruikt = 0;

        $mostRecentAanmelding = $this->getMostRecentDossierStatusOfType(Aanmelding::class);
        if(null === $mostRecentAanmelding) throw new UserException("Kan geen recente aanmelding vinden. Er is iets niet goed aan dit dossier lijkt het. Appklant ID: ".$this->appKlant->getId());
        $startDatumPlus12M = (clone $mostRecentAanmelding->getDatum())->add(\DateInterval::createFromDateString("12 month") );
        $startDatum = $mostRecentAanmelding->getDatum();

        foreach ($this->overnachtingen as $overnachting) {;
            if ($overnachting->getDatum() >= $startDatum
            && $overnachting->getDatum() <= $startDatumPlus12M
            ) {
                $nachtenGebruikt++;
            }
        }

        return $nachtenGebruikt;
    }

    /**
     * Calculate the overnights per slaper,
     * with entitled overnights per year per type.
     *
     * @return int The saldo calculated
     */
    public function calculateSaldo(): int
    {

        $recht = $this->getOvernachtingsRechtForYear($this->getDossierStatus()->getDatum()->format('Y'));
        $used = $this->getOvernachtingenUsed();

        return $recht - $used;
    }

    public function getKlant(): ?Klant
    {
        return $this->getAppKlant();
    }

    public function getKlantFieldName(): string
    {
        return "appKlant";
    }
}
