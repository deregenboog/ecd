<?php

namespace InloopBundle\Pdf;

use AppBundle\Service\NameFormatter;
use InloopBundle\Entity\Schorsing;

class PdfSchorsingEn extends \TCPDF
{
    public function __construct($html, Schorsing $schorsing)
    {
        parent::__construct();

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('De Regenboog Groep');
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->SetFont('helvetica', '', 10);

        $this->SetTitle('Suspension of '.NameFormatter::formatInformal($schorsing->getKlant()));
        $this->SetSubject('Suspension');

        $this->AddPage();
        $this->Image('img/drg-logo-142px.jpg', 10, 0, 40, 40);
        $this->writeHTMLCell(null, null, 15, 41, $this->spaceless($html));
    }

    private function spaceless($html)
    {
        return trim(preg_replace('/>\s+</', '><', $html));
    }
}
