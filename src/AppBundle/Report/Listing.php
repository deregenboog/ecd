<?php

namespace AppBundle\Report;

class Listing
{
    private $result;

    private $columns = [];

    private $ySort = false;

    private $startDate;

    private $endDate;

    private $xLabels = [];

    public function __construct(array $result, array $columns)
    {
        $this->result = $result;
        $this->columns = $columns;
    }

    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function setYSort($ySort)
    {
        $this->ySort = $ySort;

        return $this;
    }

    public function render()
    {
        $set = new \Set();

        $data = $this->initializeStructure();

        foreach ($this->result as $i => $row) {
            $values = [];
            foreach ($this->xLabels as $xLabel) {
                $nPath = $this->columns[$xLabel];
                $values[$xLabel] = $set->classicExtract($row, $nPath);
            }
            $data[$i + 1] = $values;
        }

        return $data;
    }

    protected function initializeLabels()
    {
        $xLabels = [];
        foreach (array_keys($this->columns) as $xLabel) {
            $xLabels[] = $xLabel;
        }

        $this->xLabels = $xLabels;
    }

    protected function initializeStructure()
    {
        $this->initializeLabels();

        $data = [];

        return $data;
    }
}
