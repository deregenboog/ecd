<?php

namespace TwBundle\Entity;

use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tw_huurverzoeken")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Huurverzoek
{
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     * @ORM\ManyToOne(targetEntity="Klant", inversedBy="huurverzoeken")
     * @Gedmo\Versioned
     */
    private $klant;


    /**
     * @var Huurovereenkomst
     * @ORM\OneToOne(targetEntity="Huurovereenkomst", mappedBy="huurverzoek")
     * @Gedmo\Versioned
     */
    private $huurovereenkomst;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var HuurverzoekAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="HuurverzoekAfsluiting", inversedBy="huurverzoeken", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $afsluiting;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="tw_huurverzoek_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var Project[]
     * @ORM\ManyToMany(targetEntity="Project")
     * @ORM\JoinTable(
     *     name="tw_huurverzoeken_tw_projecten",
     *     joinColumns={@ORM\JoinColumn(name="tw_huurverzoek_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tw_project_id")}
     * )
     */
    protected $projecten;

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

    public function __construct()
    {
        $this->startdatum = new \DateTime();

        $this->verslagen = new ArrayCollection();
    }

    public function __toString()
    {
        if ($this->afsluitdatum) {
            return sprintf(
                '%s (%s t/m %s)',
                $this->klant,
                $this->startdatum->format('d-m-Y'),
                $this->afsluitdatum->format('d-m-Y')
            );
        }

        return sprintf(
            '%s (vanaf %s)',
            $this->klant,
            $this->startdatum->format('d-m-Y')
        );
    }

    public function getId()
    {
        return $this->id;
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

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum(\DateTime $afsluitdatum = null)
    {
        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }

    public function getHuurovereenkomst()
    {
        return $this->huurovereenkomst;
    }

    public function isDeletable()
    {
        return false;
    }

    public function isActief(): bool
    {
        return null === $this->afsluiting;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;

        return $this;
    }

    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(HuurverzoekAfsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }

    /**
     * @return Project[]
     */
    public function getProjecten() //: ?array
    {
        return $this->projecten;
    }

    /**
     * @param Project[] $projecten
     * @return Huurverzoek
     */
    public function setProjecten($projecten): Huurverzoek
    {
        $this->projecten = $projecten;
        return $this;
    }
}
