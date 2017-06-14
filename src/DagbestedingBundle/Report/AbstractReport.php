<?php

namespace DagbestedingBundle\Report;

use AppBundle\Service\AbstractDao;
use AppBundle\Report\Table;

abstract class AbstractReport
{
    /**
     * @var AbstractDao
     */
    protected $dao;

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

    protected function build()
    {
        foreach ($this->tables as $title => $table) {
            $table = new Table($table, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => $title,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }
}
