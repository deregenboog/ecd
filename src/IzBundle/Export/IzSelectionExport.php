<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use AppBundle\Export\GenericExport;

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

    /**
     * @var array
     */
    private $headers1;

    /**
     * @var array
     */
    private $headers2;

    public function __construct($class1, array $configuration1, $class2, array $configuration2)
    {
        $this->class1 = $class1;
        $this->configuration1 = $configuration1;
        $this->headers1 = $this->getHeaders($configuration1);

        $this->class2 = $class2;
        $this->configuration2 = $configuration2;
        $this->headers2 = $this->getHeaders($configuration2);
    }

    /**
     * @return \PHPExcel_Worksheet
     */
    protected function prepare()
    {
        if (!$this->excel) {
            $this->excel = new \PHPExcel();
            $sheet = $this->excel->getActiveSheet();
            $sheet->setTitle('Deelnemers');
        } else {
            $sheet = new \PHPExcel_Worksheet($this->excel, 'Vrijwilligers');
            $this->excel->addSheet($sheet);
        }

        $column = 0;
        foreach ($this->headers as $header) {
            $sheet->setCellValueByColumnAndRow($column, 1, $header, true)
                ->getStyle()->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
            ++$column;
        }

        return $sheet;
    }

    public function create($entities)
    {
        if (!$this->excel) {
            $this->class = $this->class1;
            $this->configuration = $this->configuration1;
            $this->headers = $this->headers1;

            parent::create($entities);
        } else {
            $this->class = $this->class2;
            $this->configuration = $this->configuration2;
            $this->headers = $this->headers2;

            parent::create($entities);
            $this->excel->setActiveSheetIndex(0);
        }

        return $this;
    }

    public function getProjecten(IzDeelnemer $izDeelnemer)
    {
        $projecten = [];

        if ($izDeelnemer instanceof IzKlant) {
            foreach ($izDeelnemer->getIzHulpvragen() as $izHulpvraag) {
                $projecten[] = $izHulpvraag->getIzProject();
            }
        } elseif ($izDeelnemer instanceof IzVrijwilliger) {
            foreach ($izDeelnemer->getIzHulpaanbiedingen() as $izHulpaanbod) {
                $projecten[] = $izHulpaanbod->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getMedewerkerIntake(IzDeelnemer $izDeelnemer)
    {
        if ($izDeelnemer->getIzIntake()) {
            return $izDeelnemer->getIzIntake()->getMedewerker();
        }
    }

    public function getMedewerkers(IzDeelnemer $izDeelnemer)
    {
        $medewerkers = [];

        if ($izDeelnemer instanceof IzKlant) {
            foreach ($izDeelnemer->getIzHulpvragen() as $izHulpvraag) {
                $medewerkers[] = $izHulpvraag->getMedewerker();
            }
        } elseif ($izDeelnemer instanceof IzVrijwilliger) {
            foreach ($izDeelnemer->getIzHulpaanbiedingen() as $izHulpaanbod) {
                $medewerkers[] = $izHulpaanbod->getMedewerker();
            }
        }

        return implode(', ', array_unique($medewerkers));
    }
}
