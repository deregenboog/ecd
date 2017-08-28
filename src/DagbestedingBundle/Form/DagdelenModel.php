<?php

namespace DagbestedingBundle\Form;

use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Entity\Dagdeel;
use Doctrine\Common\Collections\Criteria;
use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Entity\Project;
use Symfony\Component\Validator\Constraints as Assert;

class DagdelenModel
{
    const DATE_FORMAT = 'd-m-Y';

    /**
     * @var Project
     */
    private $project;

    /**
     * @var \DateTime
     */
    private $datum;

    public $dagdelen = [];

    public function __construct(Project $project, \DateTime $datum)
    {
        $this->project = $project;
        $this->datum = $datum;
        $this->dagdelen = [
            Dagdeel::DAGDEEL_OCHTEND => new DagdeelModel(),
            Dagdeel::DAGDEEL_MIDDAG => new DagdeelModel(),
            Dagdeel::DAGDEEL_AVOND => new DagdeelModel(),
        ];
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function getDagdelen()
    {
        return $this->dagdelen;
    }

    public function setDagdeel(Dagdeel $dagdeel)
    {
        $this->dagdelen[$dagdeel->getDagdeel()]->setAanwezigheid($dagdeel->getAanwezigheid());

        return $this;
    }

    public function removeDagdeel(Dagdeel $dagdeel)
    {
        unset($this->dagdelen[$dagdeel->getDagdeel()]);

        return $this;
    }

    /**
     * @Assert\Valid
     */
    public function getOchtend()
    {
        return $this->getDagdeelModel(Dagdeel::DAGDEEL_OCHTEND);
    }

    public function setOchtend(DagdeelModel $dagdeelModel)
    {
        return $this->setDagdeelModel(Dagdeel::DAGDEEL_OCHTEND, $dagdeelModel);
    }

    /**
     * @Assert\Valid
     */
    public function getMiddag()
    {
        return $this->getDagdeelModel(Dagdeel::DAGDEEL_MIDDAG);
    }

    public function setMiddag(DagdeelModel $dagdeelModel)
    {
        return $this->setDagdeelModel(Dagdeel::DAGDEEL_MIDDAG, $dagdeelModel);
    }

    /**
     * @Assert\Valid
     */
    public function getAvond()
    {
        return $this->getDagdeelModel(Dagdeel::DAGDEEL_AVOND);
    }

    public function setAvond(DagdeelModel $dagdeelModel)
    {
        return $this->setDagdeelModel(Dagdeel::DAGDEEL_AVOND, $dagdeelModel);
    }

    private function getDagdeelModel($dagdeelNaam)
    {
        if (array_key_exists($dagdeelNaam, $this->dagdelen)) {
            return $this->dagdelen[$dagdeelNaam];
        }

        return;

        foreach ($this->dagdelen as $dagdeel) {
            if ($dagdeel->getDagdeel() === $dagdeelNaam) {
                return new DagdeelModel($dagdeel->getAanwezigheid());
            }
        }

        return new DagdeelModel();
    }

    private function setDagdeelModel($dagdeelNaam, DagdeelModel $dagdeelModel)
    {
        foreach ($this->dagdelen as $dagdeel) {
            if ($dagdeel->getDagdeel() === $dagdeelNaam) {
                $dagdeel->setAanwezigheid($dagdeelModel->getAanwezigheid());

                return $this;
            }
        }

        $this->addDagdeel(new Dagdeel($this->project, $this->datum, $dagdeelNaam, $dagdeelModel->getAanwezigheid()));

        return $this;
    }
}
