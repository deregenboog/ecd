<?php

namespace DagbestedingBundle\Form;

use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Entity\Dagdeel;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Entity\Traject;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class DagdelenRangeModel
{
    public const DATE_FORMAT = 'd-m-Y';

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

    /**
     * @var DagdelenRangeModel
     */
    private $dagdelenRange;

    public function __construct(Traject $traject, Project $project, AppDateRangeModel $dateRange)
    {
        $this->traject = $traject;
        $this->project = $project;
        $this->dateRange = $dateRange;
    }

    /**
     * @Assert\Valid
     */
    public function getDagdelenRange()
    {
        // create empty data structure
        $dagdelen = [];
        $datum = clone $this->dateRange->getStart();
        while ($datum <= $this->dateRange->getEnd()) {
            $key = $datum->format(self::DATE_FORMAT);
            $dagdelen[$key] = new DagdelenModel($this->project, clone $datum);
            $datum->modify('+1 day');
        }

        // fill data
        $existingDagdelen = $this->traject->getDagdelenByDateRangeAndProject($this->dateRange, $this->project);
        foreach ($existingDagdelen as $dagdeel) {
            $key = $dagdeel->getDatum()->format(self::DATE_FORMAT);
            $dagdelen[$key]->setDagdeel($dagdeel);
        }

        // remove other projects' "dagdelen"
        $existingDagdelen = $this->traject->getDagdelenByDateRangeAndNotProject($this->dateRange, $this->project);
        foreach ($existingDagdelen as $dagdeel) {
            $key = $dagdeel->getDatum()->format(self::DATE_FORMAT);
            $dagdelen[$key]->removeDagdeel($dagdeel);
        }

        return $dagdelen;
    }

    public function setDagdelenRange(array $dagdelenModels)
    {
        $dagdelen = new ArrayCollection();
        foreach ($dagdelenModels as $dagdelenModel) {
            foreach ($dagdelenModel->getDagdelen() as $dagdeelNaam => $dagdeelModel) {
                $dagdelen[] = new Dagdeel(
                    $dagdelenModel->getProject(),
                    $dagdelenModel->getDatum(),
                    $dagdeelNaam,
                    $dagdeelModel->getAanwezigheid()
                );
            }
        }

        $this->traject->updateDagdelenByDateRangeAndProject($this->dateRange, $this->project, $dagdelen);

        return $this;
    }

    public function getTraject()
    {
        return $this->traject;
    }

    //     public function getProject()
    //     {
    //         return $this->project;
    //     }

    //     public function getDateRange()
    //     {
    //         return $this->dateRange;
    //     }
}
