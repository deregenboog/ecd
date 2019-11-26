<?php

namespace UhkBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\KlantRelationInterface;
use AppBundle\Model\UsesKlantTrait;
use AppBundle\Service\NameFormatter;
use BuurtboerderijBundle\Entity\Afsluitreden;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="uhk_deelnemers",
 *     indexes={}
 * )
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Deelnemer implements KlantRelationInterface
{
    use IdentifiableTrait, ActivatableTrait, UsesKlantTrait;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $klant;

    //For KlantRelationInterface and usesKlantTrait.
    private $klantFieldName = "klant";

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $contactpersoonNazorg;

    /**
     * @var string
     *
     * @ORM\Column(nullable=false)
     */
    private $aanmeldNaam;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    protected $aanmelddatum;


    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $medewerker;

    /**
     * @var Collection|Verslag[]
     *
     * @ORM\OneToMany(targetEntity="Verslag", mappedBy="deelnemer", cascade={"persist"})
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;



    public function __construct(Klant $klant = null, Medewerker $medewerker = null)
    {
        $this->klant = $klant;
        $this->verslagen = new ArrayCollection();
        $this->medewerker = $medewerker;
        if(!$this->aanmelddatum)
        {
            $this->aanmelddatum = new \DateTime('now');
        }

    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->klant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
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
     * @return \AppBundle\Entity\Medewerker
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    /**
     * @param \AppBundle\Entity\Medewerker $medewerker
     */
    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * @return Collection|Verslag[]
     */
    public function getVerslagen()
    {
        return $this->verslagen;
    }

    /**
     * @param Verslag $verslag
     */
    public function addVerslag(Verslag $verslag)
    {
        $verslag->setDeelnemer($this);
        $this->verslagen[] = $verslag;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactpersoonNazorg(): ?string
    {
        return $this->contactpersoonNazorg;
    }

    /**
     * @param string $contactpersoonNazorg
     */
    public function setContactpersoonNazorg(string $contactpersoonNazorg): void
    {
        $this->contactpersoonNazorg = $contactpersoonNazorg;
    }

    /**
     * @return string
     */
    public function getAanmeldNaam(): ?string
    {
        return $this->aanmeldNaam;
    }

    /**
     * @param string $aanmeldNaam
     */
    public function setAanmeldNaam(string $aanmeldNaam): void
    {
        $this->aanmeldNaam = $aanmeldNaam;
    }

    /**
     * @return \DateTime
     */
    public function getAanmelddatum(): ?\DateTime
    {
        return $this->aanmelddatum;
    }

    /**
     * @param \DateTime $aanmeldDatum
     */
    public function setAanmelddatum(\DateTime $aanmeldDatum): void
    {
        $this->aanmelddatum = $aanmeldDatum;
    }


    public function getKlantFieldName()
    {
        return $this->klantFieldName;
    }
}
