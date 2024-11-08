<?php

namespace OekraineBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="oekraine_verslagen",
 *     indexes={
 *
 *         @ORM\Index(name="idx_datum", columns={"datum"}),
 *         @ORM\Index(name="idx_locatie_id", columns={"locatie_id"})
 *     }
 * )
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Verslag
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text")
     *
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @var Bezoeker
     *
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Bezoeker", inversedBy="verslagen")
     *
     * @Gedmo\Versioned
     */
    private $bezoeker;

    /**
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Locatie")
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

    public const TYPE_PSYCH = 4;
    public const TYPE_MW = 2;
    public const TYPE_INLOOP = 1;

    protected static $types = [
        self::TYPE_PSYCH => 'Psychologen verslag (Oekraine)',
        self::TYPE_MW => 'Maatschappelijk werk-verslag (Oekraine)',
        self::TYPE_INLOOP => 'Inloopverslag (Oekraine)',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="verslagType", type="integer", options={"default":1})
     *
     * @Gedmo\Versioned
     */
    private $type = self::TYPE_MW;

    public const ACCESS_PSYCH = 4;
    public const ACCESS_MW = 2;
    public const ACCESS_INLOOP = 1;

    public static $accessTypes = [
        self::ACCESS_PSYCH => 'Leesbaar voor MW-psychologen (Oekraine)',
        self::ACCESS_MW => 'Alleen leesbaar voor MW-ers (Oekraine)',
        self::ACCESS_INLOOP => 'Leesbaar voor inloop en MW (Oekraine)',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="accessType", type="integer", options={"default":1})
     *
     * @Gedmo\Versioned
     */
    private $access = self::ACCESS_INLOOP;

    public function __construct($type = self::TYPE_INLOOP)
    {
        $this->setType($type);
        $this->datum = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function getOpmerking(): string
    {
        if(is_null($this->opmerking)) return "";
        return mb_convert_encoding($this->opmerking, 'ISO-8859-1','UTF-8');
    }

    /**
     * @param string $opmerking
     */
    public function setOpmerking(string $opmerking = "")
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');

        return $this;
    }

    public function getBezoeker(): Bezoeker
    {
        return $this->bezoeker;
    }

    public function setBezoeker(Bezoeker $bezoeker): void
    {
        $this->bezoeker = $bezoeker;
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

    public function getAantalContactmomenten(): int
    {
        return $this->aantalContactmomenten;
    }

    public function setAantalContactmomenten(int $aantalContactmomenten): void
    {
        $this->aantalContactmomenten = $aantalContactmomenten;
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

    public function getAccessAsString(): string
    {
        return self::$accessTypes[$this->access];
    }
}
