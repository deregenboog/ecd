<?php

namespace AppBundle\Report;

class Grid
{
    private $result;

    private $yPath;

    private $columns = [];

    private $ySort = true;

    private $yTotals = true;

    private $yNullReplacement;

    private $startDate;

    private $endDate;

    private $xLabels = [];

    private $yLabels = [];

    public function __construct(array $result, array $columns, $yPath = null)
    {
        $this->result = $result;
        $this->columns = $columns;
        $this->yPath = $yPath;
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

    public function setYTotals($yTotals)
    {
        $this->yTotals = $yTotals;

        return $this;
    }

    public function setYSort($ySort)
    {
        $this->ySort = $ySort;

        return $this;
    }

    public function setYNullReplacement($yNullReplacement)
    {
        $this->yNullReplacement = $yNullReplacement;

        return $this;
    }

    public function render()
    {
        $set = new \Set();

        $data = $this->initializeStructure();

        foreach ($this->result as $row) {
            if ($this->yPath) {
                $yLabel = $set->classicExtract($row, $this->yPath);
                foreach ($this->xLabels as $xLabel) {
                    $nPath = $this->columns[$xLabel];
                    $data[$yLabel][$xLabel] = $set->classicExtract($row, $nPath);
                }
            } else {
                foreach ($this->xLabels as $xLabel) {
                    $nPath = $this->columns[$xLabel];
                    $data[''][$xLabel] = $set->classicExtract($row, $nPath);
                }
            }
        }

        if ($this->yNullReplacement && key_exists('', $data)) {
            $data[$this->yNullReplacement] = $data[''];
            unset($data['']);
        }

        if ($this->yTotals && $this->yPath) {
            $totals = [];
            foreach ($this->xLabels as $xLabel) {
                $totals[$xLabel] = 0;
            }
            foreach ($data as $row) {
                foreach ($this->xLabels as $xLabel) {
                    $totals[$xLabel] += $row[$xLabel];
                }
            }
            $data['Totaal'] = $totals;
        }

        return $data;
    }

    protected function initializeLabels()
    {
        $set = new \Set();

        $xLabels = [];
        $yLabels = [];
        foreach (array_keys($this->columns) as $xLabel) {
            $xLabels[] = $xLabel;
        }

        foreach ($this->result as $row) {
            if ($this->yPath) {
                $yLabel = $set->classicExtract($row, $this->yPath);
                $yLabels[] = $yLabel;
            }
        }
        if ($this->ySort) {
            sort($yLabels);
        }

        $this->xLabels = $xLabels;
        $this->yLabels = $yLabels;
    }

    protected function initializeStructure()
    {
        $this->initializeLabels();

        $data = [];
        foreach ($this->xLabels as $xLabel) {
            foreach ($this->yLabels as $yLabel) {
                $data[$yLabel][$xLabel] = 0;
            }
        }

        return $data;
    }
}
