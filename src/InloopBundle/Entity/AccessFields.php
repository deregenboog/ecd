<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Verblijfsstatus;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="inloop_access_fields")
 * @Gedmo\Loggable
 */
class AccessFields
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     * @ORM\JoinColumn(name="klant_id", referencedColumnName="id", nullable=false)
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="intake_datum", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $intakedatum;

    /**
     * @var Verblijfsstatus
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Verblijfsstatus")
     * @ORM\JoinColumn(name="verblijfstatus_id")
     * @Gedmo\Versioned
     */
    private $verblijfsstatus;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     * @ORM\JoinColumn(name="intakelocatie_id")
     * @Gedmo\Versioned
     */
    private $intakelocatie;

    /**
     * @var bool
     *
     * @ORM\Column(name="toegang_inloophuis", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $toegangInloophuis;

    /**
     * @var Locatie
     *
     * @ORM\ManyToMany(targetEntity="Locatie")
     * @ORM\JoinTable(name="inloop_access_fields_locaties")
     * @var Collection<int, Locatie>
     */
    private Collection $specifiekeLocaties;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="overigen_toegang_van", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $overigenToegangVan;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     * @ORM\JoinColumn(name="gebruikersruimte_id")
     * @Gedmo\Versioned
     */
    private $gebruikersruimte;

    public function __construct()
    {
        $this->specifiekeLocaties = new ArrayCollection();
    }

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKlant(): ?Klant
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant): self
    {
        $this->klant = $klant;
        return $this;
    }

    public function getIntakedatum(): ?\DateTime
    {
        return $this->intakedatum;
    }

    public function setIntakedatum(?\DateTime $intakedatum): self
    {
        $this->intakedatum = $intakedatum;
        return $this;
    }

    public function getVerblijfsstatus(): ?Verblijfsstatus
    {
        return $this->verblijfsstatus;
    }

    public function setVerblijfsstatus(?Verblijfsstatus $verblijfsstatus): self
    {
        $this->verblijfsstatus = $verblijfsstatus;
        return $this;
    }

    public function getIntakelocatie(): ?Locatie
    {
        return $this->intakelocatie;
    }

    public function setIntakelocatie(?Locatie $intakelocatie): self
    {
        $this->intakelocatie = $intakelocatie;
        return $this;
    }

    public function isToegangInloophuis(): ?bool
    {
        return $this->toegangInloophuis;
    }

    public function setToegangInloophuis(?bool $toegangInloophuis): self
    {
        $this->toegangInloophuis = $toegangInloophuis;
        return $this;
    }

    public function getSpecifiekeLocaties()
    {
        return $this->specifiekeLocaties;
    }

    /**
     * @param Collection|array $specifiekeLocaties
     * @return $this
     */
    public function setSpecifiekeLocaties($specifiekeLocaties): self
    {
        //Array komt vanuit fixtures.. ...
        if (is_array($specifiekeLocaties)) {
            $this->specifiekeLocaties = new ArrayCollection($specifiekeLocaties);
        } else {
            $this->specifiekeLocaties = $specifiekeLocaties;
        }
        return $this;
    }

    public function getOverigenToegangVan(): ?\DateTime
    {
        return $this->overigenToegangVan;
    }

    public function setOverigenToegangVan(?\DateTime $overigenToegangVan): self
    {
        $this->overigenToegangVan = $overigenToegangVan;
        return $this;
    }

    public function getGebruikersruimte(): ?Locatie
    {
        return $this->gebruikersruimte;
    }

    public function setGebruikersruimte(?Locatie $gebruikersruimte): self
    {
        $this->gebruikersruimte = $gebruikersruimte;
        return $this;
    }
}