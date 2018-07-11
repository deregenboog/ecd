<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\ZrmInterface;
use AppBundle\Model\ZrmTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_intakes")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Intake implements ZrmInterface
{
    use ZrmTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @todo Fix typo modifed => modified
     *
     * @ORM\Column(name="modifed", type="datetime")
     * @Gedmo\Versioned
     */
    private $modified;

    /**
     * @ORM\Column(name="intake_datum", type="date")
     * @Gedmo\Versioned
     */
    private $intakeDatum;

    /**
     * @ORM\Column(name="gezin_met_kinderen", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $gezinMetKinderen;

    /**
     * @ORM\Column(name="stagiair", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $stagiair;

    /**
     * @var IzDeelnemer
     * @ORM\OneToOne(targetEntity="IzDeelnemer", inversedBy="intake")
     * @ORM\JoinColumn(name="iz_deelnemer_id")
     * @Gedmo\Versioned
     */
    private $izDeelnemer;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $medewerker;

    /**
     * @var string
     * @ORM\Column(name="gesprek_verslag", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $gespreksverslag;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $ondernemen;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $overdag;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $ontmoeten;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $regelzaken;

    public function __construct()
    {
        $this->intakeDatum = new \DateTime('today');
    }

    public function getId()
    {
        return $this->id;
    }

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

    public function getIntakeDatum()
    {
        return $this->intakeDatum;
    }

    /**
     * @param \DateTime $intakeDatum
     */
    public function setIntakeDatum(\DateTime $intakeDatum)
    {
        $this->intakeDatum = $intakeDatum;

        return $this;
    }

    public function getIzDeelnemer()
    {
        return $this->izDeelnemer;
    }

    public function isGezinMetKinderen()
    {
        return $this->gezinMetKinderen;
    }

    public function setGezinMetKinderen($gezinMetKinderen)
    {
        $this->gezinMetKinderen = (bool) $gezinMetKinderen;

        return $this;
    }

    public function isStagiair()
    {
        return $this->stagiair;
    }

    public function setStagiair($stagiair)
    {
        $this->stagiair = (bool) $stagiair;

        return $this;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    public function setIzDeelnemer(IzDeelnemer $izDeelnemer)
    {
        $this->izDeelnemer = $izDeelnemer;

        return $this;
    }

    public function getGespreksverslag()
    {
        return $this->gespreksverslag;
    }

    public function setGespreksverslag($gespreksverslag = null)
    {
        $this->gespreksverslag = $gespreksverslag;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOndernemen()
    {
        return $this->ondernemen;
    }

    /**
     * @param bool $ondernemen
     */
    public function setOndernemen($ondernemen)
    {
        $this->ondernemen = $ondernemen;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOverdag()
    {
        return $this->overdag;
    }

    /**
     * @param bool $overdag
     */
    public function setOverdag($overdag)
    {
        $this->overdag = $overdag;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOntmoeten()
    {
        return $this->ontmoeten;
    }

    /**
     * @param bool $ontmoeten
     */
    public function setOntmoeten($ontmoeten)
    {
        $this->ontmoeten = $ontmoeten;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRegelzaken()
    {
        return $this->regelzaken;
    }

    /**
     * @param bool $regelzaken
     */
    public function setRegelzaken($regelzaken)
    {
        $this->regelzaken = $regelzaken;

        return $this;
    }
}
