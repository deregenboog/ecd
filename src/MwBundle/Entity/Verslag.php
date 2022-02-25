<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use InloopBundle\Entity\Locatie;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="verslagen",
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
     * @ORM\Column(type="text", nullable=false)
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant", inversedBy="verslagen")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
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
     * @ORM\ManyToOne(targetEntity="Contactsoort")
     * @Gedmo\Versioned
     */
    private $contactsoort;

    /**
     * @var int
     *
     * @ORM\Column(name="aanpassing_verslag", type="integer")
     * @Gedmo\Versioned
     */
    private $duur;

    /**
     * @var Verslaginventarisatie
     *
     * @ORM\OneToMany(targetEntity="Verslaginventarisatie", mappedBy="verslag", cascade={"persist"})
     */
    private $verslaginventarisaties;

    public const TYPE_MW = 1;
    public const TYPE_INLOOP = 2;

    protected static $types = [
        self::TYPE_MW => "Maatschappelijk werk-verslag",
        self::TYPE_INLOOP => "Inloopverslag",
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
        self::ACCESS_MW => "Leesbaar alleen binnen MW",
        self::ACCESS_ALL => "Leesbaar voor inloop en MW",

    ];

    /**
     * @var int
     * @ORM\Column(name="accessType", type="integer", options={"default":1})
     * @Gedmo\Versioned
     */
    private $access = self::ACCESS_ALL;

    public function __construct(Klant $klant, $type = 1)
    {
        $this->setType($type);

        $this->klant = $klant;
        $this->verslaginventarisaties = new ArrayCollection();
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
     * @return Klant
     */
    public function getKlant()
    {
        return $this->klant;
    }

    /**
     * @param Klant $klant
     */
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
     * @return \MwBundle\Entity\Verslag
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
     * @return \MwBundle\Entity\Verslag
     */
    public function setDuur($duur)
    {
        $this->duur = $duur;

        return $this;
    }

    /**
     * @return Verslaginventarisatie
     */
    public function getVerslaginventarisaties()
    {
        return $this->verslaginventarisaties;
    }

    /**
     * @param Verslaginventarisatie[] $verslaginventarisaties
     */
    public function setVerslaginventarisaties($verslaginventarisaties)
    {
        foreach ($this->verslaginventarisaties as $verslaginventarisatie) {
            $verslaginventarisatie->setVerslag(null);
        }
        $this->verslaginventarisaties = $verslaginventarisaties;

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
        if(!in_array($type,array_flip(self::$types))) throw new \InvalidArgumentException("Verslagtype kan alleen van types zijn zoals vermeld.");
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


//     /**
//      * Only one root per Verslaginventarisatie is allowed.
//      */
//     private function removeVerslaginventarisatieForRoot(Inventarisatie $rootInventarisatie)
//     {
//         if (!$rootInventarisatie->isRoot()) {
//             throw new \InvalidArgumentException('Non-root node provided!');
//         }

//         foreach ($this->verslaginventarisaties as $verslaginventarisatie) {
//             if ($verslaginventarisatie->getInventarisatie()->getRoot() === $rootInventarisatie) {
//                 $this->verslaginventarisaties->removeElement($verslaginventarisatie);
//             }
//         }

//         return $this;
//     }


}
