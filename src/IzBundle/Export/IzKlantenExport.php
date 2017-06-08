<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzKlant;
use AppBundle\Export\AbstractExport;

class IzKlantenExport extends AbstractExport
{
    /**
     * @var IzKlant[]
     */
    private $izKlanten;

    private $headers = [
        'Nummer',
        'Intakedatum',
        'Afsluitdatum',
        'Geslacht',
        'Voornaam',
        'Tussenvoegsel',
        'Achternaam',
        'Geboortedatum',
        'E-mail',
        'Adres',
        'Postcode',
        'Woonplaats',
        'Mobiel',
        'Telefoon',
        'Werkgebied',
        'Gezin met kinderen',
        'Geen post',
        'Geen e-mail',
        'Project(en)',
        'Medewerker(s)',
        'Gekoppeld',
    ];

    public function __construct(array $izKlanten)
    {
        $this->izKlanten = $izKlanten;
    }

    public function create()
    {
        $this->excel = new \PHPExcel();
        $sheet = $this->excel->getActiveSheet();

        $column = ord('A');
        $row = 1;
        foreach ($this->headers as $header) {
            $sheet->setCellValue(chr($column).$row, $header, true)
            ->getStyle()->getFont()->setBold(true);
            $sheet->getColumnDimension(chr($column))->setAutoSize(true);
            ++$column;
        }

        foreach ($this->izKlanten as $izKlant) {
            /* @var IzKlant $izKlant */
            $klant = $izKlant->getKlant();

            $projecten = [];
            foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
                $projecten[] = $izHulpvraag->getIzProject();
            }
            $medewerkers = [];
            foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
                $medewerkers[] = $izHulpvraag->getMedewerker();
            }

            $column = ord('A');
            ++$row;

            $sheet->setCellValue(chr($column).$row, $klant->getId());

            ++$column;
            if ($izKlant->getIzIntake()) {
                $sheet->setCellValue(
                    chr($column).$row,
                    \PHPExcel_Shared_Date::PHPToExcel($izKlant->getIzIntake()->getIntakeDatum()),
                    true
                    )->getStyle()->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            }

            ++$column;
            if ($izKlant->getAfsluitDatum()) {
                $sheet->setCellValue(
                    chr($column).$row,
                    \PHPExcel_Shared_Date::PHPToExcel($izKlant->getAfsluitDatum()),
                    true
                    )->getStyle()->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            }

            $sheet->setCellValue(chr(++$column).$row, (string) $klant->getGeslacht());
            $sheet->setCellValue(chr(++$column).$row, $klant->getVoornaam());
            $sheet->setCellValue(chr(++$column).$row, $klant->getTussenvoegsel());
            $sheet->setCellValue(chr(++$column).$row, $klant->getAchternaam());
            $sheet->setCellValue(chr(++$column).$row, $klant->getGeboortedatum() ? \PHPExcel_Shared_Date::PHPToExcel($klant->getGeboortedatum()) : null);
            $sheet->setCellValue(chr(++$column).$row, $klant->getEmail());
            $sheet->setCellValue(chr(++$column).$row, $klant->getAdres());
            $sheet->setCellValue(chr(++$column).$row, $klant->getPostcode());
            $sheet->setCellValue(chr(++$column).$row, $klant->getPlaats());
            $sheet->setCellValue(chr(++$column).$row, $klant->getMobiel());
            $sheet->setCellValue(chr(++$column).$row, $klant->getTelefoon());
            $sheet->setCellValue(chr(++$column).$row, $klant->getWerkgebied());

            ++$column;
            if ($izKlant->getIzIntake()) {
                $sheet->setCellValue(chr($column).$row, $izKlant->getIzIntake()->isGezinMetKinderen() ? 'ja' : 'nee');
            }

            $sheet->setCellValue(chr(++$column).$row, $klant->getGeenPost() ? 'ja' : 'nee');
            $sheet->setCellValue(chr(++$column).$row, $klant->getGeenEmail() ? 'ja' : 'nee');
            $sheet->setCellValue(chr(++$column).$row, implode(', ', array_unique($projecten)));
            $sheet->setCellValue(chr(++$column).$row, implode(', ', array_unique($medewerkers)));
            $sheet->setCellValue(chr(++$column).$row, $izKlant->isGekoppeld() ? 'ja' : 'nee');
        }

        return $this;
    }
}
