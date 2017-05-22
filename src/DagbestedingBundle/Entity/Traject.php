<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_trajecten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Traject
{
    use TimestampableTrait;

    const TYPES = [
        'wmo' => 'WMO',
        'ovk' => 'OVK',
    ];

    const RAPPORTAGETERMIJN = '+6 months';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Deelnemer
     * @ORM\ManyToOne(targetEntity="Deelnemer", inversedBy="trajecten")
     * @Gedmo\Versioned
     */
    private $deelnemer;

    /**
     * @ORM\Column
     * @Gedmo\Versioned
     */
    private $type = self::TYPES['wmo'];

    /**
     * @var ArrayCollection|Resultaatgebied[]
     * @ORM\OneToMany(targetEntity="Resultaatgebied", mappedBy="traject", cascade={"persist"})
     */
    private $resultaatgebieden;

    /**
     * @var Resultaatgebied
     * @ORM\OneToOne(targetEntity="Resultaatgebied")
     */
    private $huidigResultaatgebied;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @var ArrayCollection|Rapportage[]
     *
     * @ORM\OneToMany(targetEntity="Rapportage", mappedBy="traject", cascade={"persist"})
     */
    private $rapportages;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var Trajectbegeleider
     *
     * @ORM\ManyToOne(targetEntity="Trajectbegeleider")
     * @Gedmo\Versioned
     */
    private $begeleider;

    /**
     * @var TrajectAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="TrajectAfsluiting", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluiting;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="dagbesteding_huurverzoek_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $documenten;

    /**
     * @var ArrayCollection|Werklocatie[]
     *
     * @ORM\ManyToMany(targetEntity="Locatie")
     * @ORM\OrderBy({"naam" = "ASC"})
     */
    private $locaties;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Project")
     * @ORM\OrderBy({"naam" = "ASC"})
     */
    private $projecten;

    public function __construct()
    {
        $this->startdatum = new \DateTime();

        $this->rapportages = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
        $this->documenten = new ArrayCollection();
    }

    public function __toString()
    {
        if ($this->afsluitdatum) {
            return sprintf(
                '%s (%s t/m %s)',
                $this->deelnemer,
                $this->startdatum->format('d-m-Y'),
                $this->afsluitdatum->format('d-m-Y')
            );
        }

        return sprintf(
            '%s (vanaf %s)',
            $this->deelnemer,
            $this->startdatum->format('d-m-Y')
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDeelnemer()
    {
        return $this->deelnemer;
    }

    public function setDeelnemer(Deelnemer $deelnemer)
    {
        $this->deelnemer = $deelnemer;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum = null)
    {
        //         if ($this->startdatum) {
//             $rapportagedatum = (clone $this->startdatum)->modify(self::RAPPORTAGETERMIJN);
//             $this->removeRapportageDatum($rapportagedatum);
//         }

        $this->startdatum = $startdatum;

        $rapportagedatum = clone $startdatum;
        $rapportagedatum->modify(self::RAPPORTAGETERMIJN);
        $this->addRapportage(new Rapportage($rapportagedatum));

//         $einddatum = (clone $startdatum)->modify(self::RAPPORTAGETERMIJN);
//         $this->addRapportageDatum($rapportagedatum);

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

    public function isActief()
    {
        return $this->afsluiting === null;
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

    public function getRapportages()
    {
        return $this->rapportages;
    }

    public function addRapportage(Rapportage $rapportage)
    {
        $this->rapportages[] = $rapportage;
        $rapportage->setTraject($this);

        return $this;
    }

    public function removeRapportage(Rapportage $rapportage)
    {
        if ($this->rapportages->contains($rapportage)) {
            $this->rapportages->removeElement($rapportage);
        }

        return $this;
    }

    public function getBegeleider()
    {
        return $this->begeleider;
    }

    public function setBegeleider(Trajectbegeleider $begeleider)
    {
        $this->begeleider = $begeleider;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getHuidigResultaatgebied()
    {
        return $this->huidigResultaatgebied;
    }

    public function setHuidigResultaatgebied(Resultaatgebied $huidigResultaatgebied)
    {
        $this->huidigResultaatgebied = $huidigResultaatgebied;

        return $this;
    }

    public function getResultaatgebieden()
    {
        return $this->resultaatgebieden;
    }

    public function addResultaatgebied(Resultaatgebied $resultaatgebied)
    {
        $this->resultaatgebieden[] = $resultaatgebied;
        $resultaatgebied->setTraject($this);

        return $this;
    }

    public function getDocumenten()
    {
        return $this->documenten;
    }

    public function addDocument(Document $document)
    {
        $this->documenten[] = $document;

        return $this;
    }

    public function getLocaties()
    {
        return $this->locaties;
    }

    public function addLocatie(Locatie $locatie)
    {
        $this->locaties[] = $locatie;

        return $this;
    }

    public function getProjecten()
    {
        return $this->projecten;
    }

    public function addProject(Project $project)
    {
        $this->projecten[] = $project;

        return $this;
    }
}
