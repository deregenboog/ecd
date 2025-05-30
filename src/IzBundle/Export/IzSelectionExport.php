<?php

namespace IzBundle\Export;

use AppBundle\Export\ExportException;
use AppBundle\Export\ExportInterface;
use AppBundle\Export\GenericExport;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet;

class IzSelectionExport extends GenericExport
{
    /**
     * @var string
     */
    private $class1;

    /**
     * @var string
     */
    private $class2;

    /**
     * @var array
     */
    private $configuration1;

    /**
     * @var array
     */
    private $configuration2;

    public function __construct($class1, array $configuration1, $class2, array $configuration2, $friendlyName = null, $dao = null)
    {
        $this->headers = $this->getHeaders($configuration1);
        if ($this->headers !== $this->getHeaders($configuration2)) {
            throw new ExportException('Headers must match between configurations');
        }

        $this->class1 = $class1;
        $this->configuration1 = $configuration1;

        $this->class2 = $class2;
        $this->configuration2 = $configuration2;

        $this->friendlyName = $friendlyName;
        $this->dao = $dao;
    }

    /**
     * @return Worksheet
     */
    protected function prepare()
    {
        if ($this->excel) {
            $sheet = $this->excel->getActiveSheet();
        } else {
            $this->excel = new Spreadsheet();
            $sheet = $this->excel->getActiveSheet();
            $column = 1;
            foreach ($this->headers as $header) {
                $sheet->getCell([$column, 1])
                    ->setValue($header)
                    ->getStyle()->getFont()->setBold(true);
                $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
                ++$column;
            }
        }

        return $sheet;
    }

    public function create($entities): ExportInterface
    {
        if (!$this->excel) {
            $this->class = $this->class1;
            $this->configuration = $this->configuration1;

            parent::create($entities);
        } else {
            $this->class = $this->class2;
            $this->configuration = $this->configuration2;

            parent::create($entities);
        }

        return $this;
    }

    public function getProjecten(IzDeelnemer $izDeelnemer)
    {
        $projecten = [];

        if ($izDeelnemer instanceof IzKlant) {
            foreach ($izDeelnemer->getHulpvragen() as $hulpvraag) {
                $projecten[] = $hulpvraag->getProject();
            }
        } elseif ($izDeelnemer instanceof IzVrijwilliger) {
            foreach ($izDeelnemer->getHulpaanbiedingen() as $hulpaanbod) {
                $projecten[] = $hulpaanbod->getProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getMedewerkerIntake(IzDeelnemer $izDeelnemer)
    {
        if ($izDeelnemer->getIntake()) {
            return $izDeelnemer->getIntake()->getMedewerker();
        }
    }

    public function getMedewerkers(IzDeelnemer $izDeelnemer)
    {
        $medewerkers = [];

        if ($izDeelnemer instanceof IzKlant) {
            foreach ($izDeelnemer->getHulpvragen() as $hulpvraag) {
                $medewerkers[] = $hulpvraag->getMedewerker();
            }
        } elseif ($izDeelnemer instanceof IzVrijwilliger) {
            foreach ($izDeelnemer->getHulpaanbiedingen() as $hulpaanbod) {
                $medewerkers[] = $hulpaanbod->getMedewerker();
            }
        }

        return implode(', ', array_unique($medewerkers));
    }
}
