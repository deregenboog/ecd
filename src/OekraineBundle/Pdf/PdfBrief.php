<?php

namespace OekraineBundle\Pdf;

class PdfBrief extends \TCPDF
{
    public function __construct($html)
    {
        parent::__construct();

        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('De Regenboog Groep');
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->SetFont('helvetica', '', 10);

        $this->SetTitle('Doorverwijsbrief AMOC');
        $this->SetSubject('Doorverwijsbrief AMOC');

        $this->AddPage();
        $this->Image('img/drg-logo-142px.jpg', 10, 0, 40, 40);
        $this->writeHTMLCell(null, null, 15, 41, $this->spaceless($html));
    }

    private function spaceless($html)
    {
        return trim(preg_replace('/>\s+</', '><', $html));
    }
}
