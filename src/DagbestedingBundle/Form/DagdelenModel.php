<?php

namespace DagbestedingBundle\Form;

use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Entity\Dagdeel;
use Doctrine\Common\Collections\Criteria;
use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Entity\Project;

class DagdelenModel
{
    /**
     * @var Traject
     */
    private $traject;

    /**
     * @var Project
     */
    private $project;

    /**
     * @var AppDateRangeModel
     */
    private $dateRange;

    public function __construct(Traject $traject, Project $project, AppDateRangeModel $dateRange)
    {
        $this->traject = $traject;
        $this->project = $project;
        $this->dateRange = $dateRange;
    }

    public function getDagdelen()
    {
        $key = function (\DateTime $date) {
            return $date->format('d-m-Y');
        };

        $dagdelen = [];
        $datum = clone $this->dateRange->getStart();

        while ($datum <= $this->dateRange->getEnd()) {
            $dagdelen[$datum->format('d-m-Y')] = [
                'ochtend' => ['aanwezig' => false],
                'middag' => ['aanwezig' => false],
                'avond' => ['aanwezig' => false],
            ];
            $datum->modify('+1 day');
        }

        $criteria = new Criteria();
        $criteria
            ->where($criteria->expr()->eq('project', $this->project))
            ->andWhere($criteria->expr()->gte('datum', $this->dateRange->getStart()))
            ->andWhere($criteria->expr()->lte('datum', $this->dateRange->getEnd()))
        ;
        foreach ($this->traject->getDagdelen()->matching($criteria) as $dagdeel) {
            switch ($dagdeel->getDagdeel()) {
                case Dagdeel::DAGDEEL_OCHTEND:
                    $dagdelen[$key($dagdeel->getDatum())]['ochtend']['aanwezig'] = true;
                    break;
                case Dagdeel::DAGDEEL_MIDDAG:
                    $dagdelen[$key($dagdeel->getDatum())]['middag']['aanwezig'] = true;
                    break;
                case Dagdeel::DAGDEEL_AVOND:
                    $dagdelen[$key($dagdeel->getDatum())]['avond']['aanwezig'] = true;
                    break;
                default:
                    break;
            }
        }

        // remove "dagdelen" for other projects
        $criteria = new Criteria();
        $criteria
            ->where($criteria->expr()->neq('project', $this->project))
            ->andWhere($criteria->expr()->gte('datum', $this->dateRange->getStart()))
            ->andWhere($criteria->expr()->lte('datum', $this->dateRange->getEnd()))
        ;
        foreach ($this->traject->getDagdelen()->matching($criteria) as $dagdeel) {
            switch ($dagdeel->getDagdeel()) {
                case Dagdeel::DAGDEEL_OCHTEND:
                    unset($dagdelen[$key($dagdeel->getDatum())]['ochtend']);
                    break;
                case Dagdeel::DAGDEEL_MIDDAG:
                    unset($dagdelen[$key($dagdeel->getDatum())]['middag']);
                    break;
                case Dagdeel::DAGDEEL_AVOND:
                    unset($dagdelen[$key($dagdeel->getDatum())]['avond']);
                    break;
                default:
                    break;
            }
        }

        return $dagdelen;
    }

    /**
     * @param DagdeelModel[] $dagdelen
     */
    public function setDagdelen(array $dagdelen)
    {
        foreach ($dagdelen as $datum => $dagdeel) {
            $ochtend = new Dagdeel($this->project, new \DateTime($datum), Dagdeel::DAGDEEL_OCHTEND);
            if (@$dagdeel['ochtend']['aanwezig']) {
                $this->traject->addDagdeel($ochtend);
            } else {
                $this->traject->removeDagdeel($ochtend);
            }

            $middag = new Dagdeel($this->project, new \DateTime($datum), Dagdeel::DAGDEEL_MIDDAG);
            if (@$dagdeel['middag']['aanwezig']) {
                $this->traject->addDagdeel($middag);
            } else {
                $this->traject->removeDagdeel($middag);
            }

            $avond = new Dagdeel($this->project, new \DateTime($datum), Dagdeel::DAGDEEL_AVOND);
            if (@$dagdeel['avond']['aanwezig']) {
                $this->traject->addDagdeel($avond);
            } else {
                $this->traject->removeDagdeel($avond);
            }
        }

        return $this;
    }

    public function getTraject()
    {
        return $this->traject;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getDateRange()
    {
        return $this->dateRange;
    }
}
