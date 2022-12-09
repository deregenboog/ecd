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
            $sheet->getCellByColumnAndRow(1, $row)
                ->setValue($data['title'])
                ->getStyle()->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn(0)->setAutoSize(true);
            ++$row;
            $sheet->getCellByColumnAndRow(1, $row)
                ->setValue($report['title'])
                ->getStyle()->getFont()->setBold(true);

            ++$row;
            ++$row;
            $sheet->getCellByColumnAndRow(1, $row)
                ->setValue('Startdatum')
                ->getStyle()->getFont()->setBold(true);
            $sheet->getCellByColumnAndRow(2, $row)
                ->setValue(Date::PHPToExcel($data['startDate']))
                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
            ++$row;
            $sheet->getCellByColumnAndRow(1, $row)
                ->setValue('Einddatum')
                ->getStyle()->getFont()->setBold(true);
            $sheet->getCellByColumnAndRow(2, $row)
                ->setValue(Date::PHPToExcel($data['endDate']))
                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);

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
            $sheet->getCellByColumnAndRow(1, $row)
                ->setValue($description)
                ->getStyle()->getFont()->setBold(true);

            $column = 2;
            if (!empty($report['data'])) {
                foreach (array_keys(current($report['data'])) as $y) {
                    $sheet->getCellByColumnAndRow($column, $row)
                        ->setValue($y)
                        ->getStyle()->getFont()->setBold(true);
                    $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
                    ++$column;
                }
            }

            ++$row;
            $rowDataStart = $row;
            foreach ($report['data'] as $x => $series) {
                $sheet->getCellByColumnAndRow(1, $row)
                    ->setValue($x)
                    ->getStyle()->getFont()->setBold(true);
                ++$row;
            }

            $column = 2;
            $row = $rowDataStart;
            foreach ($report['data'] as $series) {
                foreach ($series as $value) {
                    $sheet->getCellByColumnAndRow($column, $row)->setValue($value);
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
