<?php

namespace UhkBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\DocumentInterface;
use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\KlantRelationInterface;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\UsesKlantTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="uhk_deelnemers",
 *     indexes={}
 * )
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Deelnemer implements KlantRelationInterface, DocumentSubjectInterface
{
    use IdentifiableTrait;
    use ActivatableTrait;
    use UsesKlantTrait;
    use DocumentSubjectTrait;
    use TimestampableTrait;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    private $klant;

    // For KlantRelationInterface and usesKlantTrait.
    private $klantFieldName = 'klant';

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $contactpersoonNazorg;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $aanmelder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
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
     *
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * Override from trait due to orderBy parameter.
     *
     * @var DocumentInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist","remove"}, fetch="EXTRA_LAZY")
     *
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true, onDelete="CASCADE")})
     *
     * @ORM\OrderBy({"naam" = "ASC", "id" = "DESC"})
     */
    protected $documenten;

    public function __construct(?Klant $klant = null, ?Medewerker $medewerker = null)
    {
        $this->klant = $klant;
        $this->medewerker = $this->aanmelder = $medewerker;
        $this->verslagen = new ArrayCollection();
        $this->documenten = new ArrayCollection();
        if (!$this->aanmelddatum) {
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

    public function getKlant(): Klant
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

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

    public function addVerslag(Verslag $verslag)
    {
        $verslag->setDeelnemer($this);
        $this->verslagen[] = $verslag;

        return $this;
    }

    public function getContactpersoonNazorg(): ?string
    {
        return $this->contactpersoonNazorg;
    }

    public function setContactpersoonNazorg(?string $contactpersoonNazorg): void
    {
        $this->contactpersoonNazorg = $contactpersoonNazorg;
    }

    public function getAanmelder(): ?Medewerker
    {
        return $this->aanmelder;
    }

    public function setAanmelder(Medewerker $aanmelder): void
    {
        $this->aanmelder = $aanmelder;
    }

    public function getAanmelddatum(): ?\DateTime
    {
        return $this->aanmelddatum;
    }

    public function setAanmelddatum(\DateTime $aanmeldDatum): void
    {
        $this->aanmelddatum = $aanmeldDatum;
    }

    public function getKlantFieldName(): string
    {
        return $this->klantFieldName;
    }
}
