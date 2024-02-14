<?php

namespace VillaBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\KlantRelationInterface;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VillaBundle\Entity\DossierStatus;

/**
 * @ORM\Entity
 * @ORM\Table(name="villa_slapers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Slaper implements KlantRelationInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    public const TYPE_RESPIJT = 1;
    public const TYPE_LOGEER = 2;

    public static $types = [
        self::TYPE_RESPIJT => "Respijtzorg",
        self::TYPE_LOGEER => "WMO Logeeropvang",
    ];


    /**
     * @var DossierStatus
     *
     * @ORM\OneToOne(targetEntity="DossierStatus", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $dossierStatus;

    /**
     * @var DossierStatus[]
     *
     * @ORM\OneToMany(targetEntity="DossierStatus", mappedBy="slaper")
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



    public function __construct(Klant $klant = null)
    {
        $this->appKlant = $klant;

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

    public function getDossierStatussen()
    {
        return $this->dossierStatussen;
    }


    public function getDossierStatus()
    {
        return $this->dossierStatus;
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



    public function getKlant(): ?Klant
    {
        return $this->getAppKlant();
    }

    public function getKlantFieldName(): string
    {
        return "appKlant";
    }
}
