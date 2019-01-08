<?php

namespace AppBundle\Report;

class Listing
{
    private $result;

    private $columns = [];

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

    public function render()
    {
        $data = $this->initializeStructure();

        foreach ($this->result as $i => $row) {
            $values = [];
            foreach ($this->xLabels as $xLabel) {
                $nPath = $this->columns[$xLabel];
                $values[$xLabel] = $row[$nPath];
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
