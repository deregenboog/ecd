<?php

namespace OekBundle\Export;

use AppBundle\Exception\AppException;
use AppBundle\Export\AbstractExport;
use AppBundle\Export\ExportInterface;
use OekBundle\Entity\Training;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class PresentielijstExport extends AbstractExport
{
    public function create($training): ExportInterface
    {
        if (!$training instanceof Training) {
            throw new AppException(sprintf(
                '%s::create() expects object of type %s, %s given.',
                self::class,
                Training::class,
                get_class($training)
            ));
        }

        $this->excel = new Spreadsheet();
        $sheet = $this->excel->getActiveSheet();
        $sheet->getDefaultColumnDimension()->setAutoSize(true);

        $aantal = $training->getGroep()->getAantalBijeenkomsten();
        if ($aantal > 1) {
            foreach (range(1, $aantal) as $i) {
                $sheet->getCell([$i + 1, 1])
                    ->setValue('Bijeenkomst '.$i)
                    ->getStyle()->getFont()->setBold(true);
            }
        }

        $deelnames = $training->getDeelnames();
        foreach ($deelnames as $i => $deelname) {
            $sheet->getCell([1, $i + 2])
                ->setValue((string) $deelname->getDeelnemer())
                ->getStyle()->getFont()->setBold(true);
        }

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        return $this;
    }
}
