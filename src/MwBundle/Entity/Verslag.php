<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use InloopBundle\Entity\Locatie;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="MwBundle\Repository\VerslagRepository")
 *
 * @ORM\Table(
 *     name="verslagen",
 *     indexes={
 *
 *         @ORM\Index(name="id", columns={"id", "klant_id", "created"}),
 *         @ORM\Index(name="klant_id", columns={"klant_id", "verslagType"}),
 *         @ORM\Index(name="klant_id_med_id", columns={"klant_id", "medewerker_id", "verslagType"}),
 *         @ORM\Index(name="idx_datum", columns={"datum"}),
 *         @ORM\Index(name="idx_locatie_id", columns={"locatie_id"}),
 *          @ORM\Index(name="idx_verslagtype_medewerker", columns={"verslagType","medewerker_id"}),
 *     }
 * )
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Verslag
{
    use IdentifiableTrait;
    use TimestampableTrait;

    public const TYPE_MW = 1;
    public const TYPE_INLOOP = 2;

    public const ACCESS_MW = 1;
    public const ACCESS_ALL = 2;

    public static $accessTypes = [
        self::ACCESS_MW => 'Leesbaar alleen binnen MW',
        self::ACCESS_ALL => 'Leesbaar voor inloop en MW',
    ];

    protected static $types = [
        self::TYPE_MW => 'Maatschappelijk werk-verslag',
        self::TYPE_INLOOP => 'Inloopverslag',
    ];

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="verslagen")
     *
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     *
     * @Gedmo\Versioned
     */
    private $locatie;

    /**
     * @ORM\Column(name="medewerker", type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $naamMedewerker;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     *
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $aantalContactmomenten = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="verslagType", type="integer", options={"default":1})
     *
     * @Gedmo\Versioned
     */
    private $type = self::TYPE_MW;

    /**
     * @var int
     *
     * @ORM\Column(name="accessType", type="integer", options={"default":1})
     *
     * @Gedmo\Versioned
     */
    private $access;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean",options={"default":0})
     */
    private $delenTw = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct(Klant $klant, $type = 1)
    {
        $this->setType($type);

        $this->klant = $klant;
        $this->datum = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    /**
     * @return string
     */
    public function getOpmerking()
    {
        return utf8_decode($this->opmerking);
    }

    /**
     * @param string $opmerking
     */
    public function setOpmerking($opmerking)
    {
        $this->opmerking = utf8_encode($opmerking);

        return $this;
    }

    /**
     * @return Klant
     */
    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    /**
     * @return Locatie
     */
    public function getLocatie()
    {
        return $this->locatie;
    }

    public function setLocatie(Locatie $locatie)
    {
        $this->locatie = $locatie;

        return $this;
    }

    /**
     * @return Medewerker
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getTypeAsString(): string
    {
        return self::$types[$this->getType()];
    }

    public function setType(int $type): void
    {
        if (!in_array($type, array_flip(self::$types))) {
            throw new \InvalidArgumentException('Verslagtype kan alleen van types zijn zoals vermeld.');
        }
        $this->type = $type;
    }

    public function getAccess(): int
    {
        return $this->access;
    }

    public function setAccess(int $access): void
    {
        //        if(is_null($access)) $access = self::$accessTypes[self::ACCESS_INLOOP];
        $this->access = $access;
    }

    public function getAantalContactmomenten(): int
    {
        return $this->aantalContactmomenten;
    }

    public function setAantalContactmomenten(int $aantalContactmomenten): void
    {
        $this->aantalContactmomenten = $aantalContactmomenten;
    }

    public function isDelenTw(): bool
    {
        return $this->delenTw;
    }

    public function setDelenTw(bool $delenTw): void
    {
        $this->delenTw = $delenTw;
    }

    /**
     * @return void
     *
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // Kijk of, wanneer het dossier gesloten is, de datum niet na de afgesloten datum ligt.
        if (!$this->getKlant()->getHuidigeMwStatus() instanceof Aanmelding && $this->datum > $this->getKlant()->getHuidigeMwStatus()->getDatum()) {
            $context->buildViolation('De datum van dit verslag mag niet groter zijn dan de datum waarop het dossier is afgesloten. ('.$this->getKlant()->getHuidigeMwStatus()->getDatum()->format('d-m-Y').')')
                ->atPath('datum')
                ->addViolation();
        }
    }
}
