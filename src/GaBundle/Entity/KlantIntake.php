<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Zrm;
use AppBundle\Model\ZrmInterface;
use AppBundle\Model\ZrmTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class KlantIntake extends Intake implements ZrmInterface
{
    use ZrmTrait;

    /**
     * @var Zrm[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Zrm", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="ga_gaklantintake_zrm",
     *     joinColumns={@ORM\JoinColumn(name="gaklantintake_id", unique=true)},
     *     inverseJoinColumns={@ORM\JoinColumn(unique=true)}
     * )
     */
    protected $zrms;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(name="foreign_key", nullable=false)
     * @Gedmo\Versioned
     */
    protected $klant;

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

    /**
     * @ORM\Column(name="informele_zorg", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $informeleZorg;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $dagbesteding;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $inloophuis;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $hulpverlening;

    /**
     * @ORM\Column(name="gezin_met_kinderen", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $gezinMetKinderen;

    public function __construct(Klant $klant = null)
    {
        $this->klant = $klant;
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->klant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

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

    /**
     * @return bool
     */
    public function isInformeleZorg()
    {
        return $this->informeleZorg;
    }

    /**
     * @param bool $informeleZorg
     */
    public function setInformeleZorg($informeleZorg)
    {
        $this->informeleZorg = $informeleZorg;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDagbesteding()
    {
        return $this->dagbesteding;
    }

    /**
     * @param bool $dagbesteding
     */
    public function setDagbesteding($dagbesteding)
    {
        $this->dagbesteding = $dagbesteding;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInloophuis()
    {
        return $this->inloophuis;
    }

    /**
     * @param bool $inloophuis
     */
    public function setInloophuis($inloophuis)
    {
        $this->inloophuis = $inloophuis;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHulpverlening()
    {
        return $this->hulpverlening;
    }

    /**
     * @param bool $hulpverlening
     */
    public function setHulpverlening($hulpverlening)
    {
        $this->hulpverlening = $hulpverlening;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGezinMetKinderen()
    {
        return $this->gezinMetKinderen;
    }

    /**
     * @param bool $gezinMetKinderen
     */
    public function setGezinMetKinderen($gezinMetKinderen)
    {
        $this->gezinMetKinderen = $gezinMetKinderen;

        return $this;
    }

    public function setZrm(Zrm $zrm)
    {
        $this->zrms = [$zrm];
        $this->klant->addZrm($zrm);

        return $this;
    }
}
