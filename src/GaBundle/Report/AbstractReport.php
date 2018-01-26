<?php

namespace GaBundle\Report;

use AppBundle\Report\AbstractReport as BaseAbstractReport;
use Doctrine\ORM\EntityRepository;

abstract class AbstractReport extends BaseAbstractReport
{
    /**
     * @var EntityRepository[]
     */
    protected $repositories;

    /**
     * @var \DateTime
     */
    protected $startDate;

    /**
     * @var \DateTime
     */
    protected $endDate;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $reports = [];

    protected $data;

    protected $xDescription;

    protected $yDescription;

    public function __construct($repositories)
    {
        $this->repositories = $repositories;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getReports()
    {
        $this->init();
        $this->build();

        return $this->reports;
    }
}
