<?php

namespace OekraineBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use MwBundle\Entity\Contactsoort;
use OekraineBundle\Entity\Locatie;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="oekraine_verslagen",
 *     indexes={
 *         @ORM\Index(name="idx_datum", columns={"datum"}),
 *         @ORM\Index(name="idx_locatie_id", columns={"locatie_id"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Verslag
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @var Bezoeker
     *
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Bezoeker", inversedBy="verslagen")
     * @Gedmo\Versioned
     */
    private $bezoeker;

    /**
     * @ORM\ManyToOne(targetEntity="OekraineBundle\Entity\Locatie")
     * @Gedmo\Versioned
     */
    private $locatie;

    /**
     * @ORM\Column(name="medewerker", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $naamMedewerker;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var Contactsoort
     *
     * @ORM\ManyToOne(targetEntity="MwBundle\Entity\Contactsoort")
     * @Gedmo\Versioned
     */
    private $contactsoort;

    /**
     * @var int
     *
     * @ORM\Column(name="aanpassing_verslag", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $duur = 0;

    public const TYPE_MW = 1;
    public const TYPE_INLOOP = 2;

    protected static $types = [
        self::TYPE_MW => "Maatschappelijk werk-verslag (Oekraine)",
        self::TYPE_INLOOP => "Inloopverslag (Oekraine)",
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="verslagType", type="integer", options={"default":1})
     * @Gedmo\Versioned
     */
    private $type = self::TYPE_MW;

    public const ACCESS_MW = 1;
    public const ACCESS_ALL = 2;

    public static $accessTypes = [
        self::ACCESS_MW => "Alleen leesbaar voor MW-ers (Oekraine)",
        self::ACCESS_ALL => "Leesbaar voor inloop en MW (Oekraine)",
    ];

    /**
     * @var int
     * @ORM\Column(name="accessType", type="integer", options={"default":1})
     * @Gedmo\Versioned
     */
    private $access = self::ACCESS_ALL;

    public function __construct($type = 2)
    {
        $this->setType($type);
//        $this->bezoeker = $bezoeker;
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

    /**
     * @param \DateTime $datum
     */
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
        return $this->opmerking;
    }

    /**
     * @param string $opmerking
     */
    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;

        return $this;
    }

    /**
     * @return Bezoeker
     */
    public function getBezoeker(): Bezoeker
    {
        return $this->bezoeker;
    }

    /**
     * @param Bezoeker $bezoeker
     */
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

    /**
     * @param Locatie $locatie
     */
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

    /**
     * @param Medewerker $medewerker
     */
    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * @return Contactsoort
     */
    public function getContactsoort()
    {
        return $this->contactsoort;
    }

    /**
     * @param Contactsoort $contactsoort
     *
     * @return Verslag
     */
    public function setContactsoort(Contactsoort $contactsoort)
    {
        $this->contactsoort = $contactsoort;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuur()
    {
        return $this->duur;
    }

    /**
     * @param int $duur
     *
     * @return Verslag
     */
    public function setDuur($duur)
    {
        $this->duur = $duur;

        return $this;
    }



    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    public function getTypeAsString(): string
    {
        return self::$types[$this->getType()];
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        if (!in_array($type, array_flip(self::$types))) {
            throw new \InvalidArgumentException("Verslagtype kan alleen van types zijn zoals vermeld.");
        }
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getAccess(): int
    {
        return $this->access;
    }

    /**
     * @param int $access
     */
    public function setAccess(int $access): void
    {
//        if(is_null($access)) $access = self::$accessTypes[self::ACCESS_ALL];
        $this->access = $access;
    }
}
