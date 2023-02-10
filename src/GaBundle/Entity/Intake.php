<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="ga_intakes")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Intake
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $medewerker;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $gespreksverslag;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $intakedatum;

    /**
     * @var Dossier
     *
     * @ORM\OneToOne(targetEntity="Dossier", inversedBy="intake")
     * @Gedmo\Versioned
     */
    protected $dossier;

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

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct(KlantDossier $dossier)
    {
        $this->setDossier($dossier);
        $this->intakedatum = new \DateTime();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->dossier->getKlant());
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getDossier()
    {
        return $this->dossier;
    }

    public function setDossier(Dossier $dossier)
    {
        $this->dossier = $dossier;
        $dossier->setIntake($this);

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

    /**
     * @return \DateTime
     */
    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum(\DateTime $datum)
    {
        $this->afsluitdatum = $datum;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }

    public function getGespreksverslag()
    {
        return $this->gespreksverslag;
    }

    public function setGespreksverslag($verslag = null)
    {
        $this->gespreksverslag = $verslag;

        return $this;
    }

    public function getIntakedatum()
    {
        return $this->intakedatum;
    }

    public function setIntakedatum(\DateTime $intakedatum)
    {
        $this->intakedatum = $intakedatum;

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
}
