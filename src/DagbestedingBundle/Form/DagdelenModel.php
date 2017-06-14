<?php

namespace DagbestedingBundle\Form;

use DagbestedingBundle\Entity\Traject;
use Doctrine\Common\Collections\ArrayCollection;
use DagbestedingBundle\Entity\Dagdeel;
use Doctrine\Common\Collections\Criteria;
use AppBundle\Form\Model\AppDateRangeModel;

class DagdelenModel
{
    /**
     * @var Traject
     */
    private $traject;

    /**
     * @var AppDateRangeModel
     */
    private $dateRange;

    public function __construct(Traject $traject, AppDateRangeModel $dateRange)
    {
        $this->traject = $traject;
        $this->dateRange = $dateRange;
    }

    public function getDagdelen()
    {
        $dagdelen = [];
        $datum = clone $this->dateRange->getStart();

        while ($datum <= $this->dateRange->getEnd()) {
            $dagdelen[$datum->format('d-m-Y')] = [
                'ochtendAanwezig' => false,
                'middagAanwezig' => false,
                'avondAanwezig' => false,
            ];
            $datum->modify('+1 day');
        }

        $criteria = new Criteria();
        $criteria
            ->andWhere($criteria->expr()->gte('datum', $this->dateRange->getStart()))
            ->andWhere($criteria->expr()->lte('datum', $this->dateRange->getEnd()))
        ;
        foreach ($this->traject->getDagdelen()->matching($criteria) as $dagdeel) {
            $key = $dagdeel->getDatum()->format('d-m-Y');
            switch ($dagdeel->getDagdeel()) {
                case Dagdeel::DAGDEEL_OCHTEND:
                    $dagdelen[$key]['ochtendAanwezig'] = true;
                    break;
                case Dagdeel::DAGDEEL_MIDDAG:
                    $dagdelen[$key]['middagAanwezig'] = true;
                    break;
                case Dagdeel::DAGDEEL_AVOND:
                    $dagdelen[$key]['avondAanwezig'] = true;
                    break;
                default:
                    break;
            }
        }

        return $dagdelen;
    }

    /**
     *
     * @param DagdeelModel[] $dagdelen
     */
    public function setDagdelen(array $dagdelen)
    {
        $criteria = new Criteria();
        $criteria
            ->andWhere($criteria->expr()->gte('datum', $this->dateRange->getStart()))
            ->andWhere($criteria->expr()->lte('datum', $this->dateRange->getEnd()))
        ;
        foreach ($this->traject->getDagdelen()->matching($criteria) as $dagdeel) {
            $this->traject->removeDagdeel($dagdeel);
        }

        foreach ($dagdelen as $datum => $dagdeel) {
            if ($dagdeel['ochtendAanwezig']) {
                $this->traject->addDagdeel(new Dagdeel(new \DateTime($datum), Dagdeel::DAGDEEL_OCHTEND));
            }
            if ($dagdeel['middagAanwezig']) {
                $this->traject->addDagdeel(new Dagdeel(new \DateTime($datum), Dagdeel::DAGDEEL_MIDDAG));
            }
            if ($dagdeel['avondAanwezig']) {
                $this->traject->addDagdeel(new Dagdeel(new \DateTime($datum), Dagdeel::DAGDEEL_AVOND));
            }
        }

        return $this;
    }

    public function getTraject()
    {
        return $this->traject;
    }

    public function getDateRange()
    {
        return $this->dateRange;
    }
}
