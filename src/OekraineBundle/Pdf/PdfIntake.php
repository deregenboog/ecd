<?php

namespace OekraineBundle\Pdf;

use AppBundle\Service\NameFormatter;
use OekraineBundle\Entity\Intake;

class PdfIntake extends \TCPDF
{
    public function __construct($html, Intake $intake)
    {
        parent::__construct();

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('De Regenboog Groep');
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->SetFont('helvetica', '', 10);

        if ($intake->getKlant()) {
            $this->SetTitle('Intake van '.NameFormatter::formatInformal($intake->getKlant()));
        } else {
            $this->SetTitle('Intake');
        }
        $this->SetSubject('Intake');

        $this->AddPage();
        $this->Image(('img/drg-logo-142px.jpg'), 10, 0, 40, 40);
        $this->writeHTMLCell(null, null, 15, 41, $this->spaceless($html));
    }

    private function spaceless($html)
    {
        return trim(preg_replace('/>\s+</', '><', $html));
    }
}
