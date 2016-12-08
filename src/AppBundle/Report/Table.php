<?php

namespace AppBundle\Report;

class Table
{
    private $result;

    private $xPath;

    private $yPath;

    private $nPath;

    private $xSort = true;

    private $ySort = true;

    private $xTotals = true;

    private $yTotals = true;

    private $xNullReplacement;

    private $yNullReplacement;

    private $startDate;

    private $endDate;

    private $controller;

    private $action;

    private $report;

    public function __construct(array $result, $xPath, $yPath, $nPath, $report = null)
    {
        $this->result = $result;
        $this->xPath = $xPath;
        $this->yPath = $yPath;
        $this->nPath = $nPath;
        $this->report = $report;
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

    public function setXTotals($xTotals)
    {
        $this->xTotals = $xTotals;

        return $this;
    }

    public function setYTotals($yTotals)
    {
        $this->yTotals = $yTotals;

        return $this;
    }

    public function setXSort($xSort)
    {
        $this->xSort = $xSort;

        return $this;
    }

    public function setYSort($ySort)
    {
        $this->ySort = $ySort;

        return $this;
    }

    public function setXNullReplacement($xNullReplacement)
    {
        $this->xNullReplacement = $xNullReplacement;

        return $this;
    }

    public function setYNullReplacement($yNullReplacement)
    {
        $this->yNullReplacement = $yNullReplacement;

        return $this;
    }

    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    public function render()
    {
        $set = new \Set();

        list($xValues, $yValues) = $this->getAxisLabels();

        $data = $this->initializePivotStructure(
            $xValues,
            $yValues,
            $this->xTotals,
            $this->yTotals
        );

        foreach ($this->result as $row) {
            if (empty($xValues) && empty($yValues)) {
                if ($this->xTotals && $this->yTotals) {
                    $data['Totaal']['Totaal'] = $set->classicExtract(current($this->result), $this->nPath);
                }
            } elseif (empty($yValues)) {
                foreach ($xValues as $xValue) {
                    if ($set->classicExtract($row, $this->xPath) === $xValue) {
                        $aantal = $set->classicExtract($row, $this->nPath);
                        if ($this->yTotals) {
                            $data['Totaal'][$xValue] += $aantal;
                        }
                        if ($this->xTotals && $this->yTotals) {
                            $data['Totaal']['Totaal'] += $aantal;
                        }
                    }
                }
            } else {
                foreach ($yValues as $yValue) {
                    if ($set->classicExtract($row, $this->yPath) === $yValue) {
                        if (empty($xValues)) {
                            $aantal = $set->classicExtract($row, $this->nPath);
                            if ($this->xTotals) {
                                $data[$yValue]['Totaal'] += $aantal;
                            }
                            if ($this->xTotals && $this->yTotals) {
                                $data['Totaal']['Totaal'] += $aantal;
                            }
                        } else {
                            foreach ($xValues as $xValue) {
                                if ($set->classicExtract($row, $this->xPath) === $xValue) {
                                    $aantal = $set->classicExtract($row, $this->nPath);
                                    $data[$yValue][$xValue] += $aantal;
                                    if ($this->xTotals) {
                                        $data[$yValue]['Totaal'] += $aantal;
                                    }
                                    if ($this->yTotals) {
                                        $data['Totaal'][$xValue] += $aantal;
                                    }
                                    if ($this->xTotals && $this->yTotals) {
                                        $data['Totaal']['Totaal'] += $aantal;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($this->report) {
            $data = $this->convertToLinks($data);
        }

        return $data;
    }

    protected function convertToLinks(array $data)
    {
        \App::import('Helper', 'Html');
        $helper = new \HtmlHelper();

        foreach ($data as $x => $row) {
            foreach ($row as $y => $value) {
                if (!$x || !$y) {
                    continue;
                }
                $url = array(
                    'controller' => $this->controller,
                    'action' => $this->action,
                    'Query.name' => $this->report,
                    'Query.from' => $this->startDate->format('Y-m-d'),
                    'Query.until' => $this->endDate->format('Y-m-d'),
                );
                if ($this->xPath && $y !== 'Totaal') {
                    $url[$this->xPath] = $y;
                }
                if ($this->yPath && $x !== 'Totaal') {
                    $url[$this->yPath] = $x;
                }
                $data[$x][$y] = $helper->link($value, $url);
            }
        }

        return $data;
    }

    protected function getAxisLabels()
    {
        $set = new \Set();

        $xLabels = array();
        $yLabels = array();
        foreach ($this->result as $row) {
            if ($this->xPath) {
                $xLabel = $set->classicExtract($row, $this->xPath);
                $xLabels[$xLabel] = $xLabel;
            }
            if ($this->yPath) {
                $yLabel = $set->classicExtract($row, $this->yPath);
                $yLabels[$yLabel] = $yLabel;
            }
        }
        if ($this->xSort) {
            sort($xLabels);
        }
        if ($this->ySort) {
            sort($yLabels);
        }

        return array($xLabels, $yLabels);
    }

    protected function initializePivotStructure($xLabels, $yLabels)
    {
        $data = array();
        foreach ($yLabels as $yLabel) {
            foreach ($xLabels as $xLabel) {
                $data[$yLabel][$xLabel] = 0;
            }
            if ($this->xTotals) {
                $data[$yLabel]['Totaal'] = 0;
            }
        }
        if ($this->yTotals) {
            foreach ($xLabels as $xLabel) {
                $data['Totaal'][$xLabel] = 0;
            }
        }
        if ($this->xTotals && $this->yTotals) {
            $data['Totaal']['Totaal'] = 0;
        }

        return $data;
    }
}
