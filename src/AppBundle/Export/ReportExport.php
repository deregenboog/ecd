<?php

namespace AppBundle\Export;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport extends AbstractExport
{
    public function create($data)
    {
        $this->excel = new Spreadsheet();

        foreach ($data['reports'] as $i => $report) {
            $reportTitle = substr(preg_replace('[^A-Za-z0-9]', '_', $report['title']), 0, 31);
            if (!$reportTitle) {
                $reportTitle = sprintf('Worksheet_%d', $i + 1);
            }
            if (0 === $i) {
                $sheet = $this->excel->getActiveSheet();
                $sheet->setTitle($reportTitle);
            } else {
                $sheet = new Worksheet($this->excel, $reportTitle);
                $this->excel->addSheet($sheet);
            }

            $row = 1;
            $sheet->getCell([1, $row])
                ->setValue($data['title'])
                ->getStyle()->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn(0)->setAutoSize(true);
            ++$row;
            $sheet->getCell([1, $row])
                ->setValue($report['title'])
                ->getStyle()->getFont()->setBold(true);

            ++$row;
            ++$row;
            $sheet->getCell([1, $row])
                ->setValue('Startdatum')
                ->getStyle()->getFont()->setBold(true);
            $sheet->getCell([2, $row])
                ->setValue(Date::PHPToExcel($data['startDate']))
                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD);
            ++$row;
            $sheet->getCell([1, $row])
                ->setValue('Einddatum')
                ->getStyle()->getFont()->setBold(true);
            $sheet->getCell([2, $row])
                ->setValue(Date::PHPToExcel($data['endDate']))
                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD);
            ++$row;
            $sheet->getCell([1, $row])
                ->setValue('Totaal aantal rijen')
                ->getStyle()->getFont()->setBold(true);
            if(!empty($report['data']))
            {
                $numDataRows = 0;
                $numDataRows = count($report['data']);
                if(isset($report['data']["Totaal"])) $numDataRows--;
                if(isset($report['data']["Uniek"])) $numDataRows--;

                $sheet->getCell([2, $row])
                    ->setValue($numDataRows)
                    ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
            }

            if (isset($report['xDescription']) && isset($report['yDescription'])) {
                $description = sprintf('%s / %s', $report['yDescription'], $report['xDescription']);
            } elseif (isset($report['xDescription'])) {
                $description = $report['xDescription'];
            } elseif (isset($report['yDescription'])) {
                $description = $report['yDescription'];
            } else {
                $description = '';
            }
            ++$row;
            ++$row;
            $sheet->getCell([1, $row])
                ->setValue($description)
                ->getStyle()->getFont()->setBold(true);

            $column = 2;
            if (!empty($report['data'])) {
                foreach (array_keys(current($report['data'])) as $y) {
                    $sheet->getCell([$column, $row])
                        ->setValue($y)
                        ->getStyle()->getFont()->setBold(true);
                    $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
                    ++$column;
                }
            }

            ++$row;
            $rowDataStart = $row;
            foreach ($report['data'] as $x => $series) {
                $sheet->getCell([1, $row])
                    ->setValue($x)
                    ->getStyle()->getFont()->setBold(true);
                ++$row;
            }

            $column = 2;
            $row = $rowDataStart;
            foreach ($report['data'] as $series) {
                foreach ($series as $value) {
                    $sheet->getCell([$column, $row])->setValue($value);
                    ++$column;
                }
                $column = 2;
                ++$row;
            }
        }

        $this->excel->setActiveSheetIndex(0);

        return $this;
    }
}
