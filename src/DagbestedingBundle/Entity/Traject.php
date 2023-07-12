<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use DagbestedingBundle\Form\DagdelenRangeModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_trajecten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable
 */
class Traject
{
    use IdentifiableTrait;
    use TimestampableTrait;

    public const TERMIJN_EVALUATIE = '+6 months';
    public const TERMIJN_EIND = '+1 year -1 day';

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
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $evaluatiedatum;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $einddatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $afsluitdatum;

    /**
     * @var Trajectcoach
     *
     * @ORM\ManyToOne(targetEntity="Trajectcoach", inversedBy="trajecten", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $trajectcoach;

    /**
     * @var Trajectafsluiting
     *
     * @ORM\ManyToOne(targetEntity="Trajectafsluiting", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $afsluiting;


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
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="trajecten", cascade={"persist"})
     * @ORM\OrderBy({"naam" = "ASC"})
     */
    private $projecten;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname", mappedBy="traject", cascade={"persist"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $deelnames;

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

    /**
     * @var ArrayCollection|Werkdoel[]
     *
     * @ORM\OneToMany(targetEntity="DagbestedingBundle\Entity\Werkdoel", mappedBy="traject", cascade={"persist"})
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $werkdoelen;

    public function __construct()
    {
        $this->documenten = new ArrayCollection();
        $this->locaties = new ArrayCollection();
        $this->projecten = new ArrayCollection();
        $this->deelnames = new ArrayCollection();
        $this->resultaatgebieden = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
        $this->werkdoelen = new ArrayCollection();

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
//            $rapportagedatum = clone $this->startdatum;
//            $rapportagedatum->modify(self::TERMIJN_EVALUATIE);
//            $einddatum = clone $this->startdatum;
//            $einddatum->modify(self::TERMIJN_EIND);
//            foreach ($this->rapportages as $rapportage) {
//                if ($rapportage->isDeletable()
//                    && in_array($rapportage->getDatum(), [$rapportagedatum, $einddatum])
//                ) {
//                    $this->removeRapportage($rapportage);
//                }
//            }
        }

        $this->startdatum = $startdatum;

        if (null!==$this->getEvaluatiedatum()) {
            $evaluatiedatum = clone $startdatum;
            $evaluatiedatum->modify(self::TERMIJN_EVALUATIE);
            $this->setEvaluatiedatum($evaluatiedatum);
        }

        $einddatum = clone $startdatum;
        $einddatum->modify(self::TERMIJN_EIND);
//        $this->addRapportage(new Rapportage($einddatum));
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

    /**
     * @return \DateTime
     */
    public function getEvaluatiedatum(): ?\DateTime
    {
        return $this->evaluatiedatum;
    }

    /**
     * @param \DateTime $evaluatiedatum
     * @return Traject
     */
    public function setEvaluatiedatum($evaluatiedatum): Traject
    {
        $evaluatiedatum = $evaluatiedatum ?? (new \DateTime())->modify(self::TERMIJN_EVALUATIE);
        $this->evaluatiedatum = $evaluatiedatum;
        return $this;
    }


    public function isDeletable(): bool
    {
        return 0 === count($this->dagdelen)
            && 0 === count($this->documenten)
            && 0 === count($this->verslagen)
        ;
    }

    public function isActief()
    {
        return null === $this->afsluiting;
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


    /**
     * @return Trajectcoach
     */
    public function getTrajectcoach(): ?Trajectcoach
    {
        return $this->trajectcoach;
    }

    /**
     * @param Trajectcoach $trajectcoach
     * @return Traject
     */
    public function setTrajectcoach(?Trajectcoach $trajectcoach): Traject
    {
        $this->trajectcoach = $trajectcoach;
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
        if (!$this->locaties->contains($locatie)) {
            $this->locaties[] = $locatie;
        }

        return $this;
    }

    public function getProjecten($inclusiefHistorischeProjecten = false)
    {
        return new Exception("Projecten niet meer beschikbaar op traject. Alleen via deelname");
    }

    public function addProject(Project $project)
    {
        return new Exception("Projecten niet meer beschikbaar op traject. Alleen via deelname");
    }

    /**
     * @return Deelname[]|ArrayCollection
     */
    public function getDeelnames()
    {
        return $this->deelnames;
    }

    /**
     * @param Deelname[]|ArrayCollection $deelnames
     * @return Traject
     */
    public function addDeelname($deelnames)
    {
        $this->deelnames[] = $deelnames;
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


    /**
     * @return Werkdoel[]|ArrayCollection
     */
    public function getWerkdoelen()
    {
        return $this->werkdoelen;
    }

    /**
     * @param Werkdoel[]|ArrayCollection $werkdoelen
     * @return Deelnemer
     */
    public function setWerkdoelen($werkdoelen)
    {
        $this->werkdoelen = $werkdoelen;
        return $this;
    }

    /**
     * @param Werkdoel $werkdoel
     * @return $this
     */
    public function addWerkdoel(Werkdoel $werkdoel)
    {
        $this->werkdoelen[] = $werkdoel;
        $werkdoel->setTraject($this);
        $werkdoel->setDeelnemer($this->deelnemer);

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
            foreach ($this->getDeelnames() as $deelname) {
                $project = $deelname->getProject();
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
