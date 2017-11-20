<?php

namespace ClipBundle\Export;

use AppBundle\Export\AbstractExport;

class ReportExport extends AbstractExport
{
    public function create($data)
    {
        $this->excel = new \PHPExcel();

        foreach ($data['reports'] as $i => $report) {
            if ($i === 0) {
                $sheet = $this->excel->getActiveSheet();
                if ($report['title']) {
                    $sheet->setTitle($report['title']);
                }
            } else {
                if ($report['title']) {
                    $sheet = new \PHPExcel_Worksheet($this->excel, $report['title']);
                } else {
                    $sheet = new \PHPExcel_Worksheet($this->excel);
                }
                $this->excel->addSheet($sheet);
            }

            $row = 1;
            $sheet->setCellValueByColumnAndRow(0, $row, $data['title'], true)
                ->getStyle()->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn(0)->setAutoSize(true);
            ++$row;
            $sheet->setCellValueByColumnAndRow(0, $row, $report['title'], true)
                ->getStyle()->getFont()->setBold(true);

            ++$row;
            ++$row;
            $sheet->setCellValueByColumnAndRow(0, $row, 'Startdatum', true)
                ->getStyle()->getFont()->setBold(true);
            $sheet->setCellValueByColumnAndRow(1, $row, \PHPExcel_Shared_Date::PHPToExcel($data['startDate']), true)
                ->getStyle()->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            ++$row;
            $sheet->setCellValueByColumnAndRow(0, $row, 'Einddatum', true)
                ->getStyle()->getFont()->setBold(true);
            $sheet->setCellValueByColumnAndRow(1, $row, \PHPExcel_Shared_Date::PHPToExcel($data['endDate']), true)
                ->getStyle()->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);

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
            $sheet->setCellValueByColumnAndRow(0, $row, $description, true)
                ->getStyle()->getFont()->setBold(true);

            $column = 1;
            foreach (array_keys(current($report['data'])) as $y) {
                $sheet->setCellValueByColumnAndRow($column, $row, $y, true)
                    ->getStyle()->getFont()->setBold(true);
                $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
                ++$column;
            }

            ++$row;
            $rowDataStart = $row;
            foreach ($report['data'] as $x => $series) {
                $sheet->setCellValueByColumnAndRow(0, $row, $x, true)
                    ->getStyle()->getFont()->setBold(true);
                ++$row;
            }

            $column = 1;
            $row = $rowDataStart;
            foreach ($report['data'] as $series) {
                foreach ($series as $value) {
                    $sheet->setCellValueByColumnAndRow($column, $row, $value);
                    ++$column;
                }
                $column = 1;
                ++$row;
            }
        }

        $this->excel->setActiveSheetIndex(0);

        return $this;
    }
}
