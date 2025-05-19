<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Verblijfsstatus;
use AppBundle\Model\IdentifiableTrait;
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
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\ManyToMany(targetEntity="Locatie")
     * @ORM\JoinTable(name="inloop_access_fields_locaties")
     * @ORM\JoinColumn(name="accessfields_id")
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
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     * @ORM\JoinColumn(name="gebruikersruimte_id")
     * @Gedmo\Versioned
     */
    private $gebruikersruimte;

    public function __construct()
    {   
        $this->specifiekeLocaties = new ArrayCollection();
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
        $this->intakedatum = new \DateTime();
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

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}