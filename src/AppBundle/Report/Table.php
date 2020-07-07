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

    /**
     * @var bool If true, pivot table gets initialized with null values instead of 0. This helps rendering 0 to - instead of plain 0.
     */
    private $nullAsNill = false;

    private $xTotalLabel = 'Totaal';

    private $yTotalLabel = 'Totaal';

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

    public function setXTotalLabel($xTotalLabel)
    {
        $this->xTotalLabel = $xTotalLabel;

        return $this;
    }

    public function setYTotalLabel($yTotalLabel)
    {
        $this->yTotalLabel = $yTotalLabel;

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

    /**
     * @return bool
     */
    public function isNullAsNill(): bool
    {
        return $this->nullAsNill;
    }

    /**
     * @param bool $nullAsNill
     */
    public function setNullAsNill(bool $nullAsNill): void
    {
        $this->nullAsNill = $nullAsNill;
    }

    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    public function render()
    {
        list($xValues, $yValues) = $this->getAxisLabels();

        $data = $this->initializePivotStructure(
            $xValues,
            $yValues
        );

        foreach ($this->result as $row) {
            if (empty($xValues) && empty($yValues)) {
                if ($this->xTotals && $this->yTotals) {
                    $data[$this->yTotalLabel][$this->xTotalLabel] = current($this->result)[$this->nPath];
                }
            } elseif (empty($yValues)) {
                foreach ($xValues as $xValue) {
                    if ((string) $row[$this->xPath] === (string) $xValue) {
                        $aantal = $row[$this->nPath];
                        if ($this->yTotals) {
                            $data[$this->yTotalLabel][$xValue] += $aantal;
                        }
                        if ($this->xTotals && $this->yTotals) {
                            $data[$this->yTotalLabel][$this->xTotalLabel] += $aantal;
                        }
                    }
                }
            } else {
                foreach ($yValues as $yValue) {
                    if ((string) $row[$this->yPath] === (string) $yValue) {
                        if (empty($xValues)) {
                            $aantal = $row[$this->nPath];
                            if ($this->xTotals) {
                                $data[$yValue][$this->xTotalLabel] += $aantal;
                            }
                            if ($this->xTotals && $this->yTotals) {
                                $data[$this->yTotalLabel][$this->xTotalLabel] += $aantal;
                            }
                        } else {
                            foreach ($xValues as $xValue) {
                                if ((string) $row[$this->xPath] === (string) $xValue) {
                                    $aantal = $row[$this->nPath];
                                    $data[$yValue][$xValue] += $aantal;
                                    if ($this->xTotals) {
                                        $data[$yValue][$this->xTotalLabel] += $aantal;
                                    }
                                    if ($this->yTotals) {
                                        $data[$this->yTotalLabel][$xValue] += $aantal;
                                    }
                                    if ($this->xTotals && $this->yTotals) {
                                        $data[$this->yTotalLabel][$this->xTotalLabel] += $aantal;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

//         if ($this->report) {
//             $data = $this->convertToLinks($data);
//         }

        return $data;
    }

//     protected function convertToLinks(array $data)
//     {
//         $helper = new \HtmlHelper();

//         foreach ($data as $x => $row) {
//             foreach ($row as $y => $value) {
//                 if (!$x || !$y) {
//                     continue;
//                 }
//                 $url = [
//                     'controller' => $this->controller,
//                     'action' => $this->action,
//                     'Query.name' => $this->report,
//                     'Query.from' => $this->startDate->format('Y-m-d'),
//                     'Query.until' => $this->endDate->format('Y-m-d'),
//                 ];
//                 if ($this->xPath && 'Totaal' !== $y) {
//                     $url[$this->xPath] = $y;
//                 }
//                 if ($this->yPath && 'Totaal' !== $x) {
//                     $url[$this->yPath] = $x;
//                 }
//                 $data[$x][$y] = $helper->link($value, $url);
//             }
//         }

//         return $data;
//     }

    protected function getAxisLabels()
    {
        $xLabels = [];
        $yLabels = [];
        foreach ($this->result as $row) {
            if ($this->xPath) {
                $xLabel = $row[$this->xPath];
                $xLabels[$xLabel] = $xLabel;
            }
            if ($this->yPath) {
                $yLabel = $row[$this->yPath];
                $yLabels[$yLabel] = $yLabel;
            }
        }
        if ($this->xSort) {
            sort($xLabels);
        }
        if ($this->ySort) {
            sort($yLabels);
        }

        return [$xLabels, $yLabels];
    }

    protected function initializePivotStructure($xLabels, $yLabels)
    {
        $zero = ($this->nullAsNill)?null:0;
        $data = [];
        foreach ($yLabels as $yLabel) {
            foreach ($xLabels as $xLabel) {
                $data[$yLabel][$xLabel] = $zero;
            }
            if ($this->xTotals) {
                $data[$yLabel][$this->xTotalLabel] = $zero;
            }
        }
        if ($this->yTotals) {
            foreach ($xLabels as $xLabel) {
                $data[$this->yTotalLabel][$xLabel] = $zero;
            }
        }
        if ($this->xTotals && $this->yTotals) {
            $data[$this->yTotalLabel][$this->xTotalLabel] = $zero;
        }

        return $data;
    }
}
