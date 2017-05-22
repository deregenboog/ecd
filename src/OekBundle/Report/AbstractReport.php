<?php

namespace OekBundle\Report;

use Doctrine\ORM\EntityRepository;

abstract class AbstractReport
{
    /**
     * EntityRepository.
     */
    protected $repository;

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

    protected $beginstand;

    protected $gestart;

    protected $afgesloten;

    protected $eindstand;

    protected $xPath;

    protected $yPath;

    protected $nPath;

    protected $xDescription;

    protected $yDescription;

    abstract protected function init();

    abstract protected function build();

    public function getTitle()
    {
        return $this->title;
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
