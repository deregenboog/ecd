<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Model\TimestampableTrait;
use DagbestedingBundle\Form\DagdelenRangeModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_trajecten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable
 */
class Traject
{
    use TimestampableTrait;

    const TERMIJN_RAPPORTAGE = '+6 months';
    const TERMIJN_EIND = '+1 year -1 day';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Deelnemer
     * @ORM\ManyToOne(targetEntity="Deelnemer", inversedBy="trajecten")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $deelnemer;

    /**
     * @var Trajectsoort
     * @ORM\ManyToOne(targetEntity="Trajectsoort", inversedBy="trajecten")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $soort;

    /**
     * @var Resultaatgebied
     * @ORM\OneToOne(targetEntity="Resultaatgebied", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $resultaatgebied;

    /**
     * @var ArrayCollection|Resultaatgebied[]
     * @ORM\OneToMany(targetEntity="Resultaatgebied", mappedBy="traject", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC", "id" = "DESC"})
     */
    private $resultaatgebieden;

    /**
     * @var ArrayCollection|Dagdeel[]
     * @ORM\OneToMany(targetEntity="Dagdeel", mappedBy="traject", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $dagdelen;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @var ArrayCollection|Rapportage[]
     *
     * @ORM\OneToMany(targetEntity="Rapportage", mappedBy="traject", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"datum" = "ASC", "id" = "ASC"})
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
     * @ORM\ManyToOne(targetEntity="Trajectbegeleider", inversedBy="trajecten")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $begeleider;

    /**
     * @var Trajectafsluiting
     *
     * @ORM\ManyToOne(targetEntity="Trajectafsluiting", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluiting;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\ManyToMany(targetEntity="Verslag", cascade={"persist"})
     * @ORM\JoinTable(name="dagbesteding_traject_verslag")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist"})
     * @ORM\JoinTable(name="dagbesteding_traject_document")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $documenten;

    /**
     * @var ArrayCollection|Locatie[]
     *
     * @ORM\ManyToMany(targetEntity="Locatie")
     * @ORM\JoinTable(name="dagbesteding_traject_locatie")
     * @ORM\OrderBy({"naam" = "ASC"})
     */
    private $locaties;

    /**
     * @var ArrayCollection|Project[]
     *
     * @ORM\ManyToMany(targetEntity="Project")
     * @ORM\JoinTable(name="dagbesteding_traject_project")
     * @ORM\OrderBy({"naam" = "ASC"})
     */
    private $projecten;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $ondersteuningsplanVerwerkt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deletedAt;

    public function __construct()
    {
        $this->documenten = new ArrayCollection();
        $this->locaties = new ArrayCollection();
        $this->projecten = new ArrayCollection();
        $this->rapportages = new ArrayCollection();
        $this->resultaatgebieden = new ArrayCollection();
        $this->verslagen = new ArrayCollection();

        $this->setStartdatum(new \DateTime());
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
        if ($this->startdatum) {
            $rapportagedatum = clone $this->startdatum;
            $rapportagedatum->modify(self::TERMIJN_RAPPORTAGE);
            $einddatum = clone $this->startdatum;
            $einddatum->modify(self::TERMIJN_EIND);
            foreach ($this->rapportages as $rapportage) {
                if ($rapportage->isDeletable()
                    && in_array($rapportage->getDatum(), [$rapportagedatum, $einddatum])
                ) {
                    $this->removeRapportage($rapportage);
                }
            }
        }

        $this->startdatum = $startdatum;

        $rapportagedatum = clone $startdatum;
        $rapportagedatum->modify(self::TERMIJN_RAPPORTAGE);
        $this->addRapportage(new Rapportage($rapportagedatum));

        $einddatum = clone $startdatum;
        $einddatum->modify(self::TERMIJN_EIND);
        $this->addRapportage(new Rapportage($einddatum));
        $this->setEinddatum($einddatum);

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

    public function isDeletable(): bool
    {
        foreach ($this->rapportages as $rapportage) {
            if (count($rapportage->getDocumenten()) > 0) {
                return false;
            }
        }

        return 0 === count($this->dagdelen)
            && 0 === count($this->documenten)
            && 0 === count($this->verslagen)
        ;
    }

    public function isActief()
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

    public function setAfsluiting(Trajectafsluiting $afsluiting)
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

    public function getSoort()
    {
        return $this->soort;
    }

    public function setSoort(Trajectsoort $soort)
    {
        $this->soort = $soort;

        return $this;
    }

    public function getResultaatgebied()
    {
        return $this->resultaatgebied;
    }

    public function setResultaatgebied(Resultaatgebied $resultaatgebied)
    {
        // set current
        $this->resultaatgebied = $resultaatgebied;

        // add to history
        $this->resultaatgebieden[] = $resultaatgebied;
        $resultaatgebied->setTraject($this);

        return $this;
    }

    public function getResultaatgebieden()
    {
        return $this->resultaatgebieden;
    }

    public function getResultaatgebiedsoort()
    {
        if ($this->resultaatgebied) {
            return $this->resultaatgebied->getSoort();
        }
    }

    public function setResultaatgebiedsoort(Resultaatgebiedsoort $soort)
    {
        if ($soort != $this->getResultaatgebiedsoort()) {
            $this->setResultaatgebied(new Resultaatgebied($soort));
        }

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

    public function getProjecten($inclusiefHistorischeProjecten = false)
    {
        if (!$inclusiefHistorischeProjecten) {
            return $this->projecten;
        }

        $projecten = clone $this->projecten;
        foreach ($this->dagdelen as $dagdeel) {
            if (!$projecten->contains($dagdeel->getProject())) {
                $projecten[] = $dagdeel->getProject();
            }
        }

        return $projecten;
    }

    public function addProject(Project $project)
    {
        $this->projecten[] = $project;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum($einddatum)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getDagdelen()
    {
        return $this->dagdelen;
    }

    public function addDagdeel(Dagdeel $dagdeel)
    {
        foreach ($this->dagdelen as $existing) {
            if ($dagdeel->isEqualTo($existing)) {
                // ignore, already in collection
                return $this;
            }
        }

        $this->dagdelen[] = $dagdeel;
        $dagdeel->setTraject($this);

        return $this;
    }

    public function removeDagdeel(Dagdeel $dagdeel)
    {
        foreach ($this->dagdelen as $existing) {
            if ($dagdeel->isEqualTo($existing)) {
                $this->dagdelen->removeElement($existing);
                $existing->setTraject(null);
            }
        }

        return $this;
    }

    public function countDagdelenByMonth()
    {
        $key = function (\DateTime $date) {
            return $date->format('Y-m');
        };

        $date = new \DateTime('first day of this month');
        $start = new \DateTime($this->startdatum->format('Y-m-01'));

        if (count($this->dagdelen) > 0) {
            $eersteDagdeel = $this->dagdelen[count($this->dagdelen) - 1];
            if ($eersteDagdeel->getDatum() < $this->startdatum) {
                $start = new \DateTime($eersteDagdeel->getDatum()->format('Y-m-01'));
            }
        }

        $months = [];
        while ($date >= $start) {
            $month = [
                'maand' => clone $date,
                'projecten' => [],
            ];
            foreach ($this->getProjecten(true) as $project) {
                $month['projecten'][$project->getId()] = [
                    'project' => $project,
                    'A' => 0,
                    'Z' => 0,
                    'O' => 0,
                    'V' => 0,
                ];
            }
            $months[$key($date)] = $month;
            $date->modify('-1 month');
        }

        foreach ($this->dagdelen as $dagdeel) {
            ++$months[$key($dagdeel->getDatum())]['projecten'][$dagdeel->getProject()->getId()][$dagdeel->getAanwezigheid()];
        }

        return $months;
    }

    public function getAanwezigheidByDateRangeAndProject(AppDateRangeModel $dateRange, Project $project)
    {
        // create empty data structure
        $dagdelen = [];
        $datum = clone $dateRange->getStart();
        while ($datum <= $dateRange->getEnd()) {
            $key = $datum->format(DagdelenRangeModel::DATE_FORMAT);
            $dagdelen[$key] = [
                Dagdeel::DAGDEEL_OCHTEND => '',
                Dagdeel::DAGDEEL_MIDDAG => '',
                Dagdeel::DAGDEEL_AVOND => '',
            ];
            $datum->modify('+1 day');
        }

        // fill data
        $existingDagdelen = $this->getDagdelenByDateRangeAndProject($dateRange, $project);
        foreach ($existingDagdelen as $dagdeel) {
            $key = $dagdeel->getDatum()->format(DagdelenRangeModel::DATE_FORMAT);
            $dagdelen[$key][$dagdeel->getDagdeel()] = $dagdeel->getAanwezigheid();
        }

        return $dagdelen;
    }

    public function getDagdelenByDateRangeAndProject(AppDateRangeModel $dateRange, Project $project)
    {
        // fill with existing entities
        $criteria = new Criteria();
        $criteria
            ->where($criteria->expr()->eq('project', $project))
            ->andWhere($criteria->expr()->gte('datum', $dateRange->getStart()))
            ->andWhere($criteria->expr()->lte('datum', $dateRange->getEnd()))
        ;

        return $this->getDagdelen()->matching($criteria);
    }

    public function getDagdelenByDateRangeAndNotProject(AppDateRangeModel $dateRange, Project $project)
    {
        // fill with existing entities
        $criteria = new Criteria();
        $criteria
            ->where($criteria->expr()->neq('project', $project))
            ->andWhere($criteria->expr()->gte('datum', $dateRange->getStart()))
            ->andWhere($criteria->expr()->lte('datum', $dateRange->getEnd()))
        ;

        return $this->getDagdelen()->matching($criteria);
    }

    public function updateDagdelenByDateRangeAndProject(AppDateRangeModel $dateRange, Project $project, ArrayCollection $newDagdelen)
    {
        $existingDagdelen = $this->getDagdelenByDateRangeAndProject($dateRange, $project);

        foreach ($existingDagdelen as $existingDagdeel) {
            foreach ($newDagdelen as $newDagdeel) {
                if ($newDagdeel->isEqualTo($existingDagdeel)) {
                    if ($newDagdeel->getAanwezigheid()) {
                        // update value
                        $existingDagdeel->setAanwezigheid($newDagdeel->getAanwezigheid());
                    } else {
                        // remove entity
                        $this->removeDagdeel($existingDagdeel);
                    }
                    $newDagdelen->removeElement($newDagdeel);
                    continue;
                }
            }
        }

        foreach ($newDagdelen as $newDagdeel) {
            if ($newDagdeel->getAanwezigheid()) {
                $this->addDagdeel($newDagdeel);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isOndersteuningsplanVerwerkt()
    {
        return $this->ondersteuningsplanVerwerkt;
    }

    /**
     * @param bool $ondersteuningsplanVerwerkt
     */
    public function setOndersteuningsplanVerwerkt($ondersteuningsplanVerwerkt)
    {
        $this->ondersteuningsplanVerwerkt = (bool) $ondersteuningsplanVerwerkt;

        return $this;
    }

    public function open()
    {
        $this->afsluitdatum = null;
        $this->afsluiting = null;

        return $this;
    }
}
